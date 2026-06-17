<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Layanan;
use App\Models\Pengumuman;
use App\Models\ProfilSection;

class HomeController extends Controller
{
    public function index()
    {
        $beritaTerbaru = Berita::published()->latest()->limit(4)->get();
        $pengumumanTerbaru = Pengumuman::published()->latest()->limit(3)->get();
        $galeriHighlight = Galeri::published()->ordered()->limit(6)->get();
        $layananUtama = Layanan::active()->ordered()->limit(6)->get();
        $profilSingkat = ProfilSection::where('key', 'ringkasan')->first();

        return view('pages.beranda', compact(
            'beritaTerbaru',
            'pengumumanTerbaru',
            'galeriHighlight',
            'layananUtama',
            'profilSingkat'
        ));
    }
}
