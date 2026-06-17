<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PermohonanLayanan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermohonanNotifikasiController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user instanceof User) {
            abort(403);
        }

        $isLurah = $user->hasRole(User::ROLE_LURAH);
        $since = $this->parseSince($request->query('since'));

        if ($isLurah) {
            $menungguVerifikasi = PermohonanLayanan::where(
                'status',
                PermohonanLayanan::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN
            )->count();

            $ditandatangani = PermohonanLayanan::where(
                'status',
                PermohonanLayanan::STATUS_DITANDATANGANI_KEPALA_KELURAHAN
            )->count();

            $baruQuery = PermohonanLayanan::query()
                ->with('layanan:id,nama')
                ->where('status', PermohonanLayanan::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN)
                ->latest('updated_at');

            if ($since) {
                $baruQuery->where('updated_at', '>', $since);
            } else {
                $baruQuery->whereRaw('1 = 0');
            }

            $baru = $baruQuery->limit(10)->get()->map(fn (PermohonanLayanan $p) => $this->formatItem($p));

            return response()->json([
                'role' => 'lurah',
                'menunggu_verifikasi' => $menungguVerifikasi,
                'ditandatangani' => $ditandatangani,
                'badge' => $menungguVerifikasi,
                'baru' => $baru,
                'checked_at' => now()->toIso8601String(),
            ]);
        }

        $permohonanBaru = PermohonanLayanan::where('status', PermohonanLayanan::STATUS_DIAJUKAN)->count();
        $suratDitandatangani = PermohonanLayanan::where(
            'status',
            PermohonanLayanan::STATUS_DITANDATANGANI_KEPALA_KELURAHAN
        )->count();

        $baruQuery = PermohonanLayanan::query()
            ->with('layanan:id,nama')
            ->where('status', PermohonanLayanan::STATUS_DIAJUKAN)
            ->latest('updated_at');

        if ($since) {
            $baruQuery->where('updated_at', '>', $since);
        } else {
            $baruQuery->whereRaw('1 = 0');
        }

        $baru = $baruQuery->limit(10)->get()->map(fn (PermohonanLayanan $p) => $this->formatItem($p));

        return response()->json([
            'role' => 'admin',
            'permohonan_baru' => $permohonanBaru,
            'surat_ditandatangani' => $suratDitandatangani,
            'badge' => $permohonanBaru + $suratDitandatangani,
            'baru' => $baru,
            'checked_at' => now()->toIso8601String(),
        ]);
    }

    private function parseSince(?string $since): ?Carbon
    {
        if (! is_string($since) || trim($since) === '') {
            return null;
        }

        try {
            return Carbon::parse($since);
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * @return array{id: int, nomor: string, layanan: string|null, nama: string, url: string}
     */
    private function formatItem(PermohonanLayanan $p): array
    {
        return [
            'id' => $p->id,
            'nomor' => $p->nomor,
            'layanan' => $p->layanan?->nama,
            'nama' => $p->nama,
            'url' => route('admin.permohonan.show', $p),
        ];
    }
}
