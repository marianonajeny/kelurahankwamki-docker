<?php

namespace App\Http\Controllers;

use App\Models\PermohonanLayanan;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuratPublikController extends Controller
{
    public function unduh(PermohonanLayanan $permohonan, string $token): StreamedResponse
    {
        if (! hash_equals($permohonan->suratUnduhToken(), $token)) {
            abort(403, 'Tautan unduh tidak valid.');
        }

        if (! $permohonan->canKirimSuratKeWhatsappWarga()) {
            abort(404, 'Surat tidak tersedia.');
        }

        if (! Storage::disk('public')->exists($permohonan->surat_terbit_path)) {
            abort(404, 'File surat tidak ditemukan.');
        }

        $filename = 'surat-'.preg_replace('/[^A-Za-z0-9._-]/', '-', $permohonan->nomor).'.pdf';

        return Storage::disk('public')->download(
            $permohonan->surat_terbit_path,
            $filename,
            ['Content-Type' => 'application/pdf'],
        );
    }
}
