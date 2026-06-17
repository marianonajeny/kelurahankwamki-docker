<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;

class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = Pengumuman::published()->latest()->paginate(10);

        return view('pages.pengumuman', compact('pengumumans'));
    }
}
