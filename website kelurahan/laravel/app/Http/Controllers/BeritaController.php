<?php

namespace App\Http\Controllers;

use App\Models\Berita;

class BeritaController extends Controller
{
    public function index()
    {
        $beritas = Berita::published()->latest()->paginate(9);

        return view('pages.berita.index', compact('beritas'));
    }

    public function show(Berita $berita)
    {
        if (! $berita->is_published || ($berita->published_at && $berita->published_at->isFuture())) {
            abort(404);
        }

        $beritaTerkait = Berita::published()
            ->latest()
            ->where('id', '!=', $berita->id)
            ->limit(3)
            ->get();

        return view('pages.berita.show', compact('berita', 'beritaTerkait'));
    }
}
