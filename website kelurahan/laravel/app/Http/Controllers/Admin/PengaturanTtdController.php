<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaturan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PengaturanTtdController extends Controller
{
    public function edit(): View
    {
        $ttd = Pengaturan::ttdLurah();

        return view('admin.pengaturan-ttd.edit', [
            'jabatan' => $ttd['jabatan'],
            'nama' => $ttd['nama'],
            'nip' => $ttd['nip'],
            'gambarUrl' => $ttd['gambar_url'],
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'lurah_jabatan' => ['required', 'string', 'max:120'],
            'lurah_nama' => ['required', 'string', 'max:120'],
            'lurah_nip' => ['nullable', 'string', 'max:50'],
            'lurah_ttd_gambar' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'hapus_gambar' => ['nullable', 'boolean'],
        ]);

        Pengaturan::set(Pengaturan::KEY_LURAH_JABATAN, $validated['lurah_jabatan']);
        Pengaturan::set(Pengaturan::KEY_LURAH_NAMA, $validated['lurah_nama']);
        Pengaturan::set(Pengaturan::KEY_LURAH_NIP, $validated['lurah_nip'] ?? null);

        $oldPath = Pengaturan::get(Pengaturan::KEY_LURAH_TTD_GAMBAR);

        if ($request->boolean('hapus_gambar')) {
            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }
            Pengaturan::set(Pengaturan::KEY_LURAH_TTD_GAMBAR, null);
        } elseif ($request->hasFile('lurah_ttd_gambar')) {
            Storage::disk('public')->makeDirectory('ttd');

            if ($oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            $path = $request->file('lurah_ttd_gambar')->store('ttd', 'public');
            Pengaturan::set(Pengaturan::KEY_LURAH_TTD_GAMBAR, $path);
        }

        return redirect()
            ->route('admin.permohonan.pengaturan-ttd.edit')
            ->with('success', 'Pengaturan TTD Kepala Kelurahan berhasil disimpan.');
    }
}
