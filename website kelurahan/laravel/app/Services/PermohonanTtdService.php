<?php

namespace App\Services;

use App\Models\Pengaturan;
use App\Models\PermohonanLayanan;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class PermohonanTtdService
{
    public function __construct(
        protected SuratTemplateService $suratTemplate,
    ) {}

    public function storeSignatureFromDataUrl(PermohonanLayanan $permohonan, string $dataUrl): string
    {
        if (! preg_match('/^data:image\/(png|jpeg);base64,/', $dataUrl)) {
            throw new \InvalidArgumentException('Format tanda tangan tidak valid.');
        }

        $binary = base64_decode(preg_replace('/^data:image\/\w+;base64,/', '', $dataUrl) ?: '', true);
        if ($binary === false || $binary === '') {
            throw new \InvalidArgumentException('Data tanda tangan tidak dapat dibaca.');
        }

        Storage::disk('public')->makeDirectory('ttd-permohonan');
        $path = 'ttd-permohonan/'.$permohonan->id.'-'.now()->format('YmdHis').'.png';
        Storage::disk('public')->put($path, $binary);

        return $path;
    }

    /**
     * @return array<string, mixed>
     */
    public function ttdViewData(PermohonanLayanan $permohonan, ?string $nip = null, ?User $lurah = null): array
    {
        $lurah ??= User::query()->where('role', User::ROLE_LURAH)->first();
        $ttdSrc = null;

        if (filled($permohonan->ttd_gambar_path) && Storage::disk('public')->exists($permohonan->ttd_gambar_path)) {
            $absolute = Storage::disk('public')->path($permohonan->ttd_gambar_path);
            $mime = mime_content_type($absolute) ?: 'image/png';
            $ttdSrc = 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($absolute));
        }

        return [
            'ttd_gambar_src' => $ttdSrc,
            'nama_penandatangan' => $lurah?->name ?? 'Kepala Kelurahan Kwamki',
            'nip_penandatangan' => $nip ?? $permohonan->ttd_penandatangan_nip ?? '—',
            'jabatan_penandatangan' => 'Lurah Kwamki',
        ];
    }

    /**
     * Data pratinjau TTD: gambar asli jika ada, placeholder nama lurah jika belum.
     *
     * @return array<string, mixed>
     */
    public function ttdPreviewData(PermohonanLayanan $permohonan): array
    {
        return Pengaturan::ttdViewDataForSurat();
    }

    public function applyGlobalTtdAndRegeneratePdf(PermohonanLayanan $permohonan): void
    {
        $ttdData = Pengaturan::ttdViewDataForSurat();
        $permohonan->surat_draft_html = $this->suratTemplate->render($permohonan, null, null, $ttdData);
        $permohonan->save();

        $this->suratTemplate->regeneratePdfTerbit($permohonan);
    }

    public function applyTtdAndRegeneratePdf(
        PermohonanLayanan $permohonan,
        string $dataUrl,
        ?string $nip,
        User $lurah,
    ): void {
        $path = $this->storeSignatureFromDataUrl($permohonan, $dataUrl);
        $permohonan->ttd_gambar_path = $path;
        $permohonan->ttd_penandatangan_nip = $nip;

        $ttdData = $this->ttdViewData($permohonan, $nip, $lurah);
        $permohonan->surat_draft_html = $this->suratTemplate->render($permohonan, null, null, $ttdData);
        $permohonan->save();

        $this->suratTemplate->regeneratePdfTerbit($permohonan);
    }
}
