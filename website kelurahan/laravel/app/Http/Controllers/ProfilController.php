<?php

namespace App\Http\Controllers;

use App\Models\ProfilSection;

class ProfilController extends Controller
{
    public function index()
    {
        $sections = ProfilSection::ordered()->get()->keyBy('key');

        return view('pages.profil', compact('sections'));
    }
}
