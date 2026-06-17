<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Pengaturan;
use Illuminate\View\View;

class LayananController extends Controller
{
    public function index(): View
    {
        $query = Layanan::active()->ordered();
        $filterKategori = request('kategori');
        if (is_string($filterKategori) && $filterKategori !== '') {
            $query->where('kategori', $filterKategori);
        }
        $layanans = $query->get();

        $kategoris = Layanan::active()->ordered()->pluck('kategori')->unique()->sort()->values();
        $jamLayanan = Pengaturan::get('jam_layanan', 'Senin–Jumat, 08.00–16.00 WIT');

        return view('pages.layanan', compact('layanans', 'kategoris', 'jamLayanan', 'filterKategori'));
    }

    public function show(Layanan $layanan): View
    {
        if (! $layanan->is_active) {
            abort(404);
        }

        $wa = Pengaturan::get('whatsapp');
        $jamLayanan = Pengaturan::get('jam_layanan', 'Senin–Jumat, 08.00–16.00 WIT');

        return view('pages.layanan-show', compact('layanan', 'wa', 'jamLayanan'));
    }
}
