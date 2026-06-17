<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermohonanLayananRequest;
use App\Models\Layanan;
use App\Models\Pengaturan;
use App\Models\PermohonanLayanan;
use App\Services\WhatsAppSuratService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;

class PermohonanLayananController extends Controller
{
    public function __construct(
        protected WhatsAppSuratService $whatsapp,
    ) {}

    public function create(Layanan $layanan): View|RedirectResponse
    {
        if (! $layanan->is_active || ! $layanan->menerima_permohonan_online) {
            return redirect()
                ->route('layanan.show', $layanan)
                ->withErrors(['form' => 'Layanan ini tidak menerima pengajuan online.']);
        }

        $jamLayanan = Pengaturan::get('jam_layanan', 'Senin–Jumat, 08.00–16.00 WIT');

        return view('pages.layanan-ajukan', compact('layanan', 'jamLayanan'));
    }

    public function store(PermohonanLayananRequest $request, Layanan $layanan): RedirectResponse
    {
        if (! $layanan->is_active || ! $layanan->menerima_permohonan_online) {
            return back()->withErrors(['form' => 'Layanan ini tidak menerima pengajuan online. Silakan datang ke kantor kelurahan atau hubungi kontak kami.']);
        }

        $data = $request->validated();
        unset($data['lampiran'], $data['lampiran_berkas']);

        $permohonan = DB::transaction(function () use ($request, $layanan, $data) {
            $data['nomor'] = PermohonanLayanan::generateNomor();
            $data['layanan_id'] = $layanan->id;
            $data['status'] = PermohonanLayanan::STATUS_DIAJUKAN;

            if ($request->hasFile('lampiran')) {
                Storage::disk('public')->makeDirectory('permohonan');
                $data['lampiran'] = $request->file('lampiran')->store('permohonan', 'public');
            }

            $berkasItems = [];
            foreach ($layanan->persyaratanBerkas() as $item) {
                $key = $item['key'];
                if ($request->hasFile("lampiran_berkas.{$key}")) {
                    Storage::disk('public')->makeDirectory('permohonan');
                    $file = $request->file("lampiran_berkas.{$key}");
                    $path = $file->store('permohonan', 'public');
                    $berkasItems[] = [
                        'key' => $key,
                        'label' => $item['label'],
                        'path' => $path,
                        'nama_asli' => $file->getClientOriginalName(),
                    ];
                }
            }

            if (count($berkasItems) > 0) {
                $data['lampiran_berkas'] = $berkasItems;
            }

            return PermohonanLayanan::create($data);
        });

        return redirect()
            ->route('layanan.permohonan.sukses')
            ->with('nomor_permohonan', $permohonan->nomor);
    }

    public function sukses(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('nomor_permohonan')) {
            return redirect()->route('layanan');
        }

        $nomor = $request->session()->get('nomor_permohonan');

        return view('pages.permohonan-sukses', compact('nomor'));
    }

    public function cekStatus(): View
    {
        $jamLayanan = Pengaturan::get('jam_layanan', 'Senin–Jumat, 08.00–16.00 WIT');

        return view('pages.layanan-cek-status', [
            'permohonan' => null,
            'jamLayanan' => $jamLayanan,
        ]);
    }

    public function cekStatusLookup(Request $request): View
    {
        $validated = $request->validate([
            'nomor' => ['required', 'string', 'max:40', 'regex:/^KW-[0-9]{8}-[0-9]+$/'],
            'no_hp' => ['required', 'string', 'max:20'],
        ], [
            'nomor.regex' => 'Format nomor permohonan tidak valid (contoh: KW-20260516-0001).',
        ]);

        $permohonan = PermohonanLayanan::query()
            ->with('layanan')
            ->where('nomor', $validated['nomor'])
            ->first();

        $jamLayanan = Pengaturan::get('jam_layanan', 'Senin–Jumat, 08.00–16.00 WIT');

        if (! $permohonan || ! $permohonan->matchesPhone($validated['no_hp'])) {
            return view('pages.layanan-cek-status', [
                'permohonan' => null,
                'error' => 'Nomor permohonan tidak ditemukan atau nomor HP tidak cocok.',
                'nomor_input' => $validated['nomor'],
                'jamLayanan' => $jamLayanan,
            ]);
        }

        $whatsappSent = false;
        try {
            $this->whatsapp->sendCekStatusNotification($permohonan);
            $whatsappSent = true;
        } catch (RuntimeException $exception) {
            Log::warning('Gagal kirim WhatsApp cek status', [
                'permohonan_id' => $permohonan->id,
                'nomor' => $permohonan->nomor,
                'message' => $exception->getMessage(),
            ]);
        }

        return view('pages.layanan-cek-status', [
            'permohonan' => $permohonan,
            'nomor_input' => $validated['nomor'],
            'jamLayanan' => $jamLayanan,
            'whatsappSent' => $whatsappSent,
        ]);
    }
}
