<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Layanan;
use App\Models\Pengumuman;
use App\Models\PermohonanLayanan;
use App\Models\PesanKontak;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'berita' => Berita::count(),
            'pengumuman' => Pengumuman::count(),
            'galeri' => Galeri::count(),
            'layanan' => Layanan::where('is_active', true)->count(),
            'pesan_baru' => PesanKontak::where('is_read', false)->count(),
            'permohonan_baru' => PermohonanLayanan::where('status', PermohonanLayanan::STATUS_DIAJUKAN)->count(),
            'permohonan_antrian' => PermohonanLayanan::where(
                'status',
                PermohonanLayanan::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN
            )->count(),
        ];

        $beritaTerbaru = Berita::latest('published_at')->limit(5)->get();
        $pengumumanTerbaru = Pengumuman::latest()->limit(5)->get();
        $pesanTerbaru = PesanKontak::where('is_read', false)->latest()->limit(5)->get();
        $permohonanTerbaru = PermohonanLayanan::with('layanan')->latest()->limit(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'beritaTerbaru',
            'pengumumanTerbaru',
            'pesanTerbaru',
            'permohonanTerbaru',
        ));
    }
}
