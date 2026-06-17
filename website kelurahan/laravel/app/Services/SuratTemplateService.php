<?php

namespace App\Services;

use App\Models\Layanan;
use App\Models\PermohonanLayanan;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class SuratTemplateService
{
    public function render(
        PermohonanLayanan $permohonan,
        ?string $nomorSurat = null,
        ?string $tanggalSurat = null,
        array $extra = [],
    ): string {
        $permohonan->loadMissing('layanan');

        $nomorSurat ??= $permohonan->nomor_surat ?? '—';
        $tanggal = $tanggalSurat
            ? Carbon::parse($tanggalSurat)
            : ($permohonan->tanggal_surat ?? now());

        $data = array_merge([
            'permohonan' => $permohonan,
            'nomor_surat' => $nomorSurat,
            'tanggal_surat_teks' => $tanggal->locale('id')->translatedFormat('d F Y'),
            'tempat_tanggal_lahir' => $this->formatTempatTanggalLahir($permohonan),
            'alamat_teks' => $this->formatAlamat($permohonan->alamat),
        ], $this->logoKopData($permohonan), $extra);

        return View::make($this->templateFor($permohonan), $data)->render();
    }

    public function regeneratePdfTerbit(PermohonanLayanan $permohonan): void
    {
        if (! filled($permohonan->surat_draft_html)) {
            throw new \RuntimeException('Draft surat belum tersedia.');
        }

        $options = new Options;
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('defaultFont', 'Times-Roman');

        $permohonan->loadMissing('layanan');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($permohonan->surat_draft_html);
        $dompdf->setPaper($this->paperSizeFor($permohonan), 'portrait');
        $dompdf->render();

        Storage::disk('public')->makeDirectory('surat-terbit');
        $path = 'surat-terbit/'.$permohonan->id.'-'.now()->format('YmdHis').'.pdf';
        Storage::disk('public')->put($path, $dompdf->output());

        $permohonan->surat_terbit_path = $path;
        $permohonan->surat_diterbitkan_at = now();
        $permohonan->save();
    }

    protected function paperSizeFor(PermohonanLayanan $permohonan): string
    {
        return 'A4';
    }

    protected function templateFor(PermohonanLayanan $permohonan): string
    {
        return match ($permohonan->layanan?->slug) {
            Layanan::SLUG_SKTM => 'admin.permohonan.surat.sktm',
            Layanan::SLUG_DOMISILI => 'admin.permohonan.surat.domisili',
            Layanan::SLUG_BELUM_MENIKAH => 'admin.permohonan.surat.belum-menikah',
            Layanan::SLUG_KELAHIRAN => 'admin.permohonan.surat.kelahiran',
            Layanan::SLUG_PINDAH => 'admin.permohonan.surat.pindah',
            Layanan::SLUG_USAHA => 'admin.permohonan.surat.usaha',
            default => 'admin.permohonan.surat.default',
        };
    }

    /**
     * @return array<string, mixed>
     */
    protected function logoKopData(PermohonanLayanan $permohonan): array
    {
        if (! in_array($permohonan->layanan?->slug, Layanan::slugsSuratResmi(), true)) {
            return [];
        }

        $logoPath = public_path('images/logo-kab-mimika.png');
        if (! is_file($logoPath)) {
            return ['logo_kop_src' => null];
        }

        $mime = mime_content_type($logoPath) ?: 'image/png';

        return [
            'logo_kop_src' => 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($logoPath)),
        ];
    }

    protected function formatAlamat(?string $alamat): string
    {
        $alamat = trim((string) $alamat);

        return $alamat !== '' ? mb_convert_case($alamat, MB_CASE_TITLE, 'UTF-8') : '—';
    }

    protected function formatTempatTanggalLahir(PermohonanLayanan $permohonan): string
    {
        $tempat = trim((string) $permohonan->tempat_lahir);
        $tanggal = $permohonan->tanggal_lahir?->locale('id')->translatedFormat('d F Y');

        if ($tempat !== '' && $tanggal) {
            return $tempat.', '.$tanggal;
        }

        if ($tempat !== '') {
            return $tempat;
        }

        return $tanggal ?: '—';
    }
}
