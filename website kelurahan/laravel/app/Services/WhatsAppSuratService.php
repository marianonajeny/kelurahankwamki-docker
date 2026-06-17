<?php

namespace App\Services;

use App\Models\PermohonanLayanan;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class WhatsAppSuratService
{
    public function buildCekStatusMessage(PermohonanLayanan $permohonan): string
    {
        $permohonan->loadMissing('layanan');

        $lines = [
            '*Status Permohonan — Kelurahan Kwamki*',
            '',
            '*Kode permohonan:* '.$permohonan->nomor,
            '',
            'Nama pemohon: '.$permohonan->nama,
            'NIK: '.$permohonan->nik,
            'Status: '.PermohonanLayanan::statusLabel($permohonan->status),
            'Layanan: '.($permohonan->layanan?->nama ?? '-'),
            'Diajukan: '.$permohonan->created_at->translatedFormat('d F Y H:i'),
        ];

        if ($permohonan->diproses_at) {
            $lines[] = 'Mulai diproses: '.$permohonan->diproses_at->translatedFormat('d F Y H:i');
        }

        if ($permohonan->selesai_at) {
            $lines[] = 'Selesai: '.$permohonan->selesai_at->translatedFormat('d F Y H:i');
        }

        if ($permohonan->ditandatangani_at) {
            $lines[] = 'Ditandatangani Kepala Kelurahan: '.$permohonan->ditandatangani_at->translatedFormat('d F Y H:i');
        }

        if ($permohonan->catatan_admin && in_array(
            PermohonanLayanan::normalizeStatus($permohonan->status),
            [
                PermohonanLayanan::STATUS_SELESAI,
                PermohonanLayanan::STATUS_DITOLAK,
                PermohonanLayanan::STATUS_REVISI_DARI_KEPALA_KELURAHAN,
            ],
            true
        )) {
            $lines[] = '';
            $lines[] = 'Catatan: '.$permohonan->catatan_admin;
        }

        $lines[] = '';
        $lines[] = 'Cek status kapan saja: '.route('layanan.cek-status');
        $lines[] = '';
        $lines[] = 'Kelurahan Kwamki — Kabupaten Mimika, Papua Tengah';

        return implode("\n", $lines);
    }

    public function sendCekStatusNotification(PermohonanLayanan $permohonan): void
    {
        $target = $permohonan->whatsappTargetE164();
        if ($target === null) {
            throw new RuntimeException('Nomor HP pemohon tidak valid.');
        }

        $this->sendTextViaWaha($target, $this->buildCekStatusMessage($permohonan));
    }

    public function buildPenolakanTemplate(PermohonanLayanan $permohonan): string
    {
        $permohonan->loadMissing('layanan');

        return implode("\n", [
            'Yth. '.$permohonan->nama.',',
            '',
            'Permohonan Anda dengan nomor *'.$permohonan->nomor.'* untuk layanan '.($permohonan->layanan?->nama ?? '-').' tidak dapat kami proses.',
            '',
            'Alasan:',
            '[isi alasan penolakan di sini]',
            '',
            'Silakan perbaiki data/berkas atau hubungi kantor kelurahan untuk informasi lebih lanjut.',
            '',
            'Kelurahan Kwamki',
        ]);
    }

    public function sendCustomTextToPemohon(PermohonanLayanan $permohonan, string $message): void
    {
        $target = $permohonan->whatsappTargetE164();
        if ($target === null) {
            throw new RuntimeException('Nomor HP pemohon tidak valid.');
        }

        $text = trim($message);
        if ($text === '') {
            throw new RuntimeException('Pesan WhatsApp tidak boleh kosong.');
        }

        $this->sendTextViaWaha($target, $text);
    }

    public function sendSuratPdfViaApi(PermohonanLayanan $permohonan): void
    {
        if (! $permohonan->canKirimSuratKeWhatsappWarga()) {
            throw new RuntimeException('Surat belum siap dikirim ke pemohon.');
        }

        $target = $permohonan->whatsappTargetE164();
        if ($target === null) {
            throw new RuntimeException('Nomor HP pemohon tidak valid.');
        }

        $permohonan->loadMissing('layanan');
        $caption = implode("\n", [
            'Yth. '.$permohonan->nama.',',
            '',
            'Surat permohonan *'.$permohonan->nomor.'* ('.($permohonan->layanan?->nama ?? 'layanan kelurahan').') telah ditandatangani dan terlampir.',
            '',
            'Kelurahan Kwamki',
        ]);

        $pdfUrl = $permohonan->suratPdfDownloadUrl() ?? $permohonan->suratPdfPublicUrl();
        if ($pdfUrl === null) {
            throw new RuntimeException('File surat tidak ditemukan.');
        }

        $filename = 'surat-'.$permohonan->nomor.'.pdf';
        $this->sendFileViaWaha($target, $caption, $pdfUrl, $filename);
    }

    protected function sendTextViaWaha(string $target, string $message): void
    {
        $this->wahaRequest('/api/sendText', [
            'session' => $this->wahaSession(),
            'chatId' => $this->toChatId($target),
            'text' => $message,
        ]);
    }

    protected function sendFileViaWaha(string $target, string $caption, string $fileUrl, string $filename): void
    {
        try {
            $this->wahaRequest('/api/sendFile', [
                'session' => $this->wahaSession(),
                'chatId' => $this->toChatId($target),
                'caption' => $caption,
                'file' => [
                    'url' => $fileUrl,
                    'filename' => $filename,
                    'mimetype' => 'application/pdf',
                ],
            ]);
        } catch (RuntimeException $exception) {
            if (! str_contains($exception->getMessage(), 'Plus version')) {
                throw $exception;
            }

            $this->sendTextViaWaha($target, implode("\n", [
                $caption,
                '',
                'Unduh surat PDF:',
                $fileUrl,
            ]));
        }
    }

    protected function wahaRequest(string $endpoint, array $body): void
    {
        if (config('whatsapp.driver') !== 'waha') {
            throw new RuntimeException('Pengiriman WhatsApp otomatis belum diaktifkan (driver bukan waha).');
        }

        $apiKey = config('whatsapp.waha.api_key');
        if (blank($apiKey)) {
            throw new RuntimeException('WAHA API key belum dikonfigurasi.');
        }

        $baseUrl = rtrim((string) config('whatsapp.waha.base_url'), '/');
        $response = Http::withHeaders([
            'X-Api-Key' => $apiKey,
            'Content-Type' => 'application/json',
        ])->post($baseUrl.$endpoint, $body);

        if (! $response->successful()) {
            $error = $response->json('message')
                ?? $response->json('error')
                ?? 'HTTP '.$response->status();

            throw new RuntimeException('WAHA API gagal: '.$error);
        }

        $body = $response->json();
        if (is_array($body) && isset($body['error']) && ! isset($body['id'])) {
            $error = $body['message'] ?? $body['error'];
            throw new RuntimeException('WAHA menolak pengiriman: '.$error);
        }
    }

    protected function wahaSession(): string
    {
        return (string) config('whatsapp.waha.session', 'default');
    }

    protected function toChatId(string $e164): string
    {
        return $e164.'@c.us';
    }
}
