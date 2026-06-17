<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PermohonanLayananUpdateRequest;
use App\Models\Pengaturan;
use App\Models\PermohonanLayanan;
use App\Models\User;
use App\Services\PermohonanTtdService;
use App\Services\WhatsAppSuratService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use RuntimeException;
use Throwable;

class PermohonanLayananController extends Controller
{
    public function __construct(
        protected PermohonanTtdService $permohonanTtd,
        protected WhatsAppSuratService $whatsapp,
    ) {}

    public function index(Request $request): View
    {
        $user = $request->user();
        $query = PermohonanLayanan::query()->with('layanan')->latest();

        if ($user?->hasRole(User::ROLE_LURAH)) {
            $query->antrianLurah();
            $statusFilter = PermohonanLayanan::normalizeStatus(
                $request->input('status', PermohonanLayanan::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN)
            );
            if ($statusFilter && in_array($statusFilter, [
                PermohonanLayanan::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN,
                PermohonanLayanan::STATUS_DITANDATANGANI_KEPALA_KELURAHAN,
                PermohonanLayanan::STATUS_SELESAI,
            ], true)) {
                $query->where('status', $statusFilter);
            }
        } else {
            $statusFilter = PermohonanLayanan::normalizeStatus($request->input('status'));
            if ($statusFilter && in_array($statusFilter, PermohonanLayanan::statuses(), true)) {
                $query->where('status', $statusFilter);
            }

            if ($request->filled('layanan_id')) {
                $query->where('layanan_id', (int) $request->layanan_id);
            }
        }

        $permohonans = $query->paginate(20)->withQueryString();
        $layanans = \App\Models\Layanan::ordered()->get(['id', 'nama']);

        return view('admin.permohonan.index', compact('permohonans', 'layanans'));
    }

    public function show(Request $request, PermohonanLayanan $permohonan): View
    {
        $user = $request->user();

        if ($user?->hasRole(User::ROLE_LURAH) && ! $permohonan->canLurahView()) {
            abort(403);
        }

        $permohonan->load('layanan');

        $suratIframeSrc = null;
        if ($user?->hasRole(User::ROLE_LURAH)) {
            if ($permohonan->hasSuratTerbit()) {
                $suratIframeSrc = route('admin.permohonan.surat.tampil', $permohonan)
                    .'?v='.($permohonan->updated_at?->timestamp ?? time());
            } elseif ($permohonan->hasSuratDraft() || $permohonan->hasSuratSiapTerbit()) {
                $suratIframeSrc = route('admin.permohonan.surat.preview', $permohonan);
            }
        }

        $penolakanTemplate = null;
        if ($user?->isAdmin() && PermohonanLayanan::normalizeStatus($permohonan->status) === PermohonanLayanan::STATUS_DIAJUKAN) {
            $penolakanTemplate = $this->whatsapp->buildPenolakanTemplate($permohonan);
        }

        return view('admin.permohonan.show', compact('permohonan', 'suratIframeSrc', 'penolakanTemplate'));
    }

    public function terima(Request $request, PermohonanLayanan $permohonan): RedirectResponse
    {
        if (! $request->user()?->isAdmin()) {
            abort(403);
        }

        if (PermohonanLayanan::normalizeStatus($permohonan->status) !== PermohonanLayanan::STATUS_DIAJUKAN) {
            return back()->withErrors(['status' => 'Hanya permohonan berstatus Diajukan yang dapat diterima.']);
        }

        $permohonan->applyStatus(PermohonanLayanan::STATUS_DIPROSES_ADMIN);
        $permohonan->save();

        return redirect()
            ->route('admin.permohonan.show', $permohonan)
            ->with('success', 'Permohonan diterima. Lanjutkan susun surat untuk proses TTD.');
    }

    public function tolak(Request $request, PermohonanLayanan $permohonan): RedirectResponse
    {
        if (! $request->user()?->isAdmin()) {
            abort(403);
        }

        if (PermohonanLayanan::normalizeStatus($permohonan->status) !== PermohonanLayanan::STATUS_DIAJUKAN) {
            return back()->withErrors(['status' => 'Hanya permohonan berstatus Diajukan yang dapat ditolak.']);
        }

        $validated = $request->validate([
            'pesan_penolakan' => ['required', 'string', 'max:2000'],
        ]);

        try {
            $this->whatsapp->sendCustomTextToPemohon($permohonan, $validated['pesan_penolakan']);
            $permohonan->purgeFilesAndDelete();
        } catch (RuntimeException $exception) {
            return back()
                ->withErrors(['whatsapp' => $exception->getMessage()])
                ->withInput();
        }

        return redirect()
            ->route('admin.permohonan.index')
            ->with('success', 'Permohonan ditolak. Pemberitahuan WhatsApp telah dikirim ke pemohon.');
    }

    public function update(PermohonanLayananUpdateRequest $request, PermohonanLayanan $permohonan): RedirectResponse
    {
        $data = $request->validated();
        $targetStatus = PermohonanLayanan::normalizeStatus($data['status']);
        /** @var User|null $user */
        $user = $request->user();

        if (! $permohonan->canUpdateStatusTo($targetStatus, $user)) {
            return back()->withErrors(['status' => 'Status tidak dapat diubah untuk peran akun Anda.'])->withInput();
        }

        $permohonan->fill([
            'nama' => $data['nama'],
            'nik' => $data['nik'],
            'no_hp' => $data['no_hp'],
            'email' => $data['email'] ?? null,
            'alamat' => $data['alamat'],
            'keperluan' => $data['keperluan'],
        ]);
        $permohonan->catatan_admin = $data['catatan_admin'] ?? null;
        $permohonan->applyStatus($targetStatus);
        $permohonan->save();

        return redirect()->route('admin.permohonan.show', $permohonan)->with('success', 'Permohonan diperbarui.');
    }

    public function lanjutkan(Request $request, PermohonanLayanan $permohonan): RedirectResponse
    {
        $user = $request->user();
        if (! $permohonan->canAdvance($user)) {
            return back()->withErrors(['status' => 'Permohonan ini tidak dapat dilanjutkan.']);
        }

        $next = $permohonan->nextStatusFor($user);
        if ($next === null) {
            return back()->withErrors(['status' => 'Status lanjutan tidak valid untuk permohonan ini.']);
        }

        try {
            if ($next === PermohonanLayanan::STATUS_DITANDATANGANI_KEPALA_KELURAHAN) {
                $pengaturanTtd = Pengaturan::ttdLurah();
                if (blank($pengaturanTtd['nama'])) {
                    return back()->withErrors([
                        'ttd' => 'Lengkapi Pengaturan TTD terlebih dahulu sebelum verifikasi.',
                    ]);
                }

                if (! $permohonan->hasSuratUntukLurah()) {
                    return back()->withErrors([
                        'status' => 'Surat belum siap ditandatangani. Minta admin menyelesaikan Susun Surat dan menerbitkan PDF.',
                    ]);
                }

                $this->permohonanTtd->applyGlobalTtdAndRegeneratePdf($permohonan);
            }

            $permohonan->applyStatus($next);
            $permohonan->save();
        } catch (Throwable $exception) {
            Log::error('Gagal memproses lanjutkan permohonan layanan.', [
                'permohonan_id' => $permohonan->id,
                'nomor_permohonan' => $permohonan->nomor,
                'current_status' => $permohonan->status,
                'next_status' => $next,
                'user_id' => $user?->id,
                'exception' => $exception->getMessage(),
            ]);

            return back()->withErrors([
                'status' => 'Terjadi kendala saat memproses status permohonan. Silakan coba lagi.',
            ]);
        }

        $message = match ($next) {
            PermohonanLayanan::STATUS_DIPROSES_ADMIN => 'Permohonan sedang diproses admin.',
            PermohonanLayanan::STATUS_TERVERIFIKASI_ADMIN => 'Permohonan terverifikasi admin.',
            PermohonanLayanan::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN => 'Permohonan dikirim ke kepala kelurahan.',
            PermohonanLayanan::STATUS_DITANDATANGANI_KEPALA_KELURAHAN => 'Permohonan sudah diverifikasi dan ditandatangani kepala kelurahan.',
            PermohonanLayanan::STATUS_SELESAI => 'Permohonan ditandai selesai.',
            default => 'Status permohonan diperbarui.',
        };

        if ($request->input('redirect') === 'show' || $request->query('redirect') === 'show') {
            return redirect()->route('admin.permohonan.show', $permohonan)->with('success', $message);
        }

        return redirect()->back()->with('success', $message);
    }

    public function prosesLanjutSurat(PermohonanLayanan $permohonan): RedirectResponse
    {
        if (PermohonanLayanan::normalizeStatus($permohonan->status) !== PermohonanLayanan::STATUS_DIPROSES_ADMIN) {
            return back()->withErrors([
                'status' => 'Proses lanjut pembuatan surat hanya bisa dilakukan saat status permohonan sedang diproses.',
            ]);
        }

        $note = '[SYSTEM] Proses lanjut pembuatan surat keterangan telah dijalankan pada '.now()->translatedFormat('d M Y H:i').'.';
        $currentNote = trim((string) $permohonan->catatan_admin);

        if (! str_contains($currentNote, '[SYSTEM] Proses lanjut pembuatan surat keterangan')) {
            $permohonan->catatan_admin = $currentNote === '' ? $note : $currentNote."\n\n".$note;
            $permohonan->save();
        }

        return redirect()
            ->route('admin.permohonan.show', $permohonan)
            ->with('success', 'Proses lanjut pembuatan surat keterangan berhasil dijalankan.');
    }

    public function kirimKeKepalaKelurahan(Request $request, PermohonanLayanan $permohonan): RedirectResponse
    {
        if (! $permohonan->canKirimKeKepalaKelurahan($request->user())) {
            return back()->withErrors(['status' => 'Permohonan tidak dapat dikirim ke Kepala Kelurahan.']);
        }

        $permohonan->applyStatus(PermohonanLayanan::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN);
        $permohonan->save();

        return back()->with('success', 'Permohonan dikirim ke antrian Kepala Kelurahan.');
    }

    public function mintaRevisi(Request $request, PermohonanLayanan $permohonan): RedirectResponse
    {
        $user = $request->user();
        if (! $user?->hasRole(User::ROLE_LURAH)) {
            abort(403);
        }

        $validated = $request->validate([
            'catatan_revisi' => ['required', 'string', 'max:2000'],
        ]);

        $permohonan->catatan_admin = $validated['catatan_revisi'];
        $permohonan->applyStatus(PermohonanLayanan::STATUS_REVISI_DARI_KEPALA_KELURAHAN);
        $permohonan->save();

        return redirect()
            ->route('admin.permohonan.show', $permohonan)
            ->with('success', 'Permintaan revisi dikirim ke admin kelurahan.');
    }

    public function kirimWhatsapp(Request $request): View
    {
        if ($request->user()?->hasRole(User::ROLE_LURAH)) {
            abort(403);
        }

        $permohonans = PermohonanLayanan::query()
            ->with('layanan')
            ->whereNotNull('surat_terbit_path')
            ->where('surat_terbit_path', '!=', '')
            ->whereIn('status', [
                PermohonanLayanan::STATUS_DITANDATANGANI_KEPALA_KELURAHAN,
                PermohonanLayanan::STATUS_SELESAI,
            ])
            ->latest()
            ->paginate(20);

        return view('admin.permohonan.kirim-whatsapp', compact('permohonans'));
    }

    public function kirimSuratKeWhatsappWarga(PermohonanLayanan $permohonan): RedirectResponse
    {
        try {
            $this->whatsapp->sendSuratPdfViaApi($permohonan);

            return back()->with('success', 'Surat PDF berhasil dikirim ke pemohon via WhatsApp.');
        } catch (RuntimeException $exception) {
            return back()->withErrors(['whatsapp' => $exception->getMessage()]);
        }
    }
}
