<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermohonanLayanan;
use App\Models\User;
use App\Services\PermohonanTtdService;
use App\Services\SuratTemplateService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuratPermohonanController extends Controller
{
    public function __construct(
        protected SuratTemplateService $suratTemplate,
        protected PermohonanTtdService $permohonanTtd,
    ) {}

    public function susunSurat(Request $request, PermohonanLayanan $permohonan): View|RedirectResponse
    {
        $this->authorizeAdmin($request);
        $permohonan->load('layanan');

        return view('admin.permohonan.susun-surat', compact('permohonan'));
    }

    public function storeSusunSurat(Request $request, PermohonanLayanan $permohonan): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:100'],
            'tanggal_surat' => ['required', 'date'],
        ]);

        $this->simpanDraftSurat($permohonan, $validated['nomor_surat'], $validated['tanggal_surat']);

        return redirect()
            ->route('admin.permohonan.susun-surat', $permohonan)
            ->with('success', 'Draft surat disimpan.');
    }

    public function terbitkan(Request $request, PermohonanLayanan $permohonan): RedirectResponse
    {
        $this->authorizeAdmin($request);

        if ($request->filled('nomor_surat') && $request->filled('tanggal_surat')) {
            $this->simpanDraftSurat(
                $permohonan,
                $request->string('nomor_surat')->toString(),
                $request->string('tanggal_surat')->toString(),
            );
        } elseif (! $permohonan->hasSuratDraft()) {
            return back()->withErrors(['surat' => 'Isi nomor dan tanggal surat terlebih dahulu.']);
        }

        try {
            $this->suratTemplate->regeneratePdfTerbit($permohonan);
            $permohonan->refresh();
        } catch (\Throwable $e) {
            return back()->withErrors(['surat' => 'Gagal menerbitkan PDF: '.$e->getMessage()]);
        }

        if ($request->boolean('kirim_ke_lurah')) {
            $this->kirimKeAntrianLurah($permohonan);
        } elseif ($permohonan->canKirimKeKepalaKelurahan($request->user())) {
            $this->kirimKeAntrianLurah($permohonan);
        }

        $redirect = $request->input('redirect') === 'susun-surat'
            ? route('admin.permohonan.susun-surat', $permohonan)
            : route('admin.permohonan.show', $permohonan);

        return redirect($redirect)->with('success', 'Surat PDF berhasil diterbitkan.');
    }

    public function kirimKeKepalaKelurahanUntukTtd(Request $request, PermohonanLayanan $permohonan): RedirectResponse
    {
        $this->authorizeAdmin($request);

        $validated = $request->validate([
            'nomor_surat' => ['required', 'string', 'max:100'],
            'tanggal_surat' => ['required', 'date'],
        ]);

        $this->simpanDraftSurat($permohonan, $validated['nomor_surat'], $validated['tanggal_surat']);

        try {
            $this->suratTemplate->regeneratePdfTerbit($permohonan);
            $permohonan->refresh();
        } catch (\Throwable $e) {
            return back()->withErrors(['surat' => 'Gagal menerbitkan PDF: '.$e->getMessage()]);
        }

        $this->kirimKeAntrianLurah($permohonan);

        return redirect()
            ->route('admin.permohonan.susun-surat', $permohonan)
            ->with('success', 'Surat diterbitkan dan dikirim ke Kepala Kelurahan untuk verifikasi.');
    }

    public function preview(Request $request, PermohonanLayanan $permohonan): Response
    {
        $this->authorizeSuratAccess($request);

        $nomorSurat = $request->input('nomor_surat', $permohonan->nomor_surat);
        $tanggalSurat = $request->input('tanggal_surat', optional($permohonan->tanggal_surat)->format('Y-m-d'));

        $extra = [];
        if ($request->boolean('with_ttd')) {
            $extra = $this->permohonanTtd->ttdPreviewData($permohonan);
        }

        $html = $this->suratTemplate->render($permohonan, $nomorSurat, $tanggalSurat, $extra);

        return $this->noCacheHtmlResponse($html);
    }

    public function unduh(Request $request, PermohonanLayanan $permohonan): StreamedResponse|RedirectResponse
    {
        $this->authorizeSuratAccess($request);

        if (! $permohonan->hasSuratTerbit() && filled($permohonan->surat_draft_html)) {
            try {
                $this->suratTemplate->regeneratePdfTerbit($permohonan);
                $permohonan->refresh();
            } catch (\Throwable) {
                //
            }
        }

        if (! $permohonan->hasSuratTerbit()) {
            return redirect()
                ->route('admin.permohonan.show', $permohonan)
                ->withErrors(['surat' => 'PDF surat belum tersedia.']);
        }

        return $this->noCachePdfResponse(
            Storage::disk('public')->response(
                $permohonan->surat_terbit_path,
                'surat-'.$permohonan->nomor.'.pdf',
                ['Content-Type' => 'application/pdf'],
            )
        );
    }

    public function tampil(Request $request, PermohonanLayanan $permohonan): StreamedResponse|Response|RedirectResponse
    {
        $this->authorizeSuratAccess($request);

        if ($permohonan->hasSuratTerbit()) {
            try {
                return $this->noCachePdfResponse(
                    Storage::disk('public')->response(
                        $permohonan->surat_terbit_path,
                        'surat-'.$permohonan->nomor.'.pdf',
                        ['Content-Type' => 'application/pdf'],
                    )
                );
            } catch (\Throwable) {
                try {
                    $this->suratTemplate->regeneratePdfTerbit($permohonan);
                    $permohonan->refresh();

                    if ($permohonan->hasSuratTerbit()) {
                        return $this->noCachePdfResponse(
                            Storage::disk('public')->response(
                                $permohonan->surat_terbit_path,
                                'surat-'.$permohonan->nomor.'.pdf',
                                ['Content-Type' => 'application/pdf'],
                            )
                        );
                    }
                } catch (\Throwable) {
                    //
                }
            }
        }

        if ($permohonan->hasSuratDraft() || (filled($permohonan->nomor_surat) && filled($permohonan->tanggal_surat))) {
            $html = $permohonan->surat_draft_html
                ?: $this->suratTemplate->render($permohonan);

            return $this->noCacheHtmlResponse($html);
        }

        return redirect()
            ->route('admin.permohonan.show', $permohonan)
            ->withErrors(['surat' => 'Surat belum tersedia.']);
    }

    protected function noCacheHtmlResponse(string $html): Response
    {
        return response($html)
            ->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * @param  StreamedResponse  $response
     */
    protected function noCachePdfResponse(StreamedResponse $response): StreamedResponse
    {
        $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
        $response->headers->set('Pragma', 'no-cache');

        return $response;
    }

    protected function simpanDraftSurat(PermohonanLayanan $permohonan, string $nomorSurat, string $tanggalSurat): void
    {
        $permohonan->nomor_surat = $nomorSurat;
        $permohonan->tanggal_surat = $tanggalSurat;
        $permohonan->surat_draft_html = $this->suratTemplate->render($permohonan, $nomorSurat, $tanggalSurat);
        $permohonan->save();
    }

    protected function kirimKeAntrianLurah(PermohonanLayanan $permohonan): void
    {
        if (PermohonanLayanan::normalizeStatus($permohonan->status) === PermohonanLayanan::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN) {
            return;
        }

        $permohonan->applyStatus(PermohonanLayanan::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN);
        $permohonan->save();
    }

    protected function authorizeAdmin(Request $request): void
    {
        $user = $request->user();
        if (! $user instanceof User || ! $user->isAdmin()) {
            abort(403);
        }
    }

    protected function authorizeSuratAccess(Request $request): void
    {
        $user = $request->user();
        if (! $user instanceof User || ! $user->canAccessAdmin()) {
            abort(403);
        }
    }
}
