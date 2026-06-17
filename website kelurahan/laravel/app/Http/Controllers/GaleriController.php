<?php

namespace App\Http\Controllers;

use App\Models\Galeri;

class GaleriController extends Controller
{
    public function index()
    {
        $galeris = Galeri::published()->ordered()->get();
        $kategoris = $galeris->pluck('kategori')->unique()->values();

        return view('pages.galeri', compact('galeris', 'kategoris'));
    }
}
