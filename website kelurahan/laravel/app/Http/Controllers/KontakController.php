<?php

namespace App\Http\Controllers;

use App\Models\PesanKontak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class KontakController extends Controller
{
    public function index()
    {
        return view('pages.kontak');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:20'],
            'subjek' => ['required', 'string', 'max:150'],
            'pesan' => ['required', 'string', 'max:2000'],
        ]);

        PesanKontak::create($validated);

        Log::info('Pesan kontak baru', $validated);

        return back()->with('success', 'Pesan Anda telah terkirim. Terima kasih telah menghubungi Kelurahan Kwamki.');
    }
}
