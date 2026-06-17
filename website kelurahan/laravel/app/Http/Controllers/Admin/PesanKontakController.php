<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PesanKontak;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class PesanKontakController extends Controller
{
    public function index(Request $request): View
    {
        if (! PesanKontak::tableReady()) {
            $pesans = new LengthAwarePaginator([], 0, 20);

            return view('admin.pesan.index', compact('pesans'));
        }

        $query = PesanKontak::query()->latest();

        if ($request->get('status') === 'belum_dibaca') {
            $query->where('is_read', false);
        }

        $pesans = $query->paginate(20)->withQueryString();

        return view('admin.pesan.index', compact('pesans'));
    }

    public function show(PesanKontak $pesan): View
    {
        if (! $pesan->is_read) {
            $pesan->update(['is_read' => true]);
        }

        return view('admin.pesan.show', compact('pesan'));
    }

    public function markUnread(PesanKontak $pesan): RedirectResponse
    {
        $pesan->update(['is_read' => false]);

        return redirect()->route('admin.pesan.show', $pesan)->with('success', 'Pesan ditandai belum dibaca.');
    }
}
