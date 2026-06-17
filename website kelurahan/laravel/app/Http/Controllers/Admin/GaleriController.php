<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GaleriRequest;
use App\Models\Galeri;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;

class GaleriController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('admin.only', except: ['index', 'show']),
        ];
    }

    public function index()
    {
        $galeris = Galeri::ordered()->paginate(15);

        return view('admin.galeri.index', compact('galeris'));
    }

    public function show(string $galeri)
    {
        $item = Galeri::findOrFail($galeri);

        return view('admin.galeri.show', ['galeri' => $item]);
    }

    public function create()
    {
        return view('admin.galeri.create');
    }

    public function store(GaleriRequest $request)
    {
        $data = $this->prepareData($request);
        $data['gambar'] = $request->file('gambar')->store('galeri', 'public');

        Galeri::create($data);

        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil ditambahkan.');
    }

    public function edit(string $galeri)
    {
        $item = Galeri::findOrFail($galeri);

        return view('admin.galeri.edit', ['galeri' => $item]);
    }

    public function update(GaleriRequest $request, string $galeri)
    {
        $item = Galeri::findOrFail($galeri);
        $data = $this->prepareData($request);

        if ($request->hasFile('gambar')) {
            if ($item->gambar) {
                Storage::disk('public')->delete($item->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('galeri', 'public');
        }

        $item->update($data);

        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil diperbarui.');
    }

    public function destroy(string $galeri)
    {
        $item = Galeri::findOrFail($galeri);

        if ($item->gambar) {
            Storage::disk('public')->delete($item->gambar);
        }

        $item->delete();

        return redirect()->route('admin.galeri.index')->with('success', 'Foto galeri berhasil dihapus.');
    }

    private function prepareData(GaleriRequest $request): array
    {
        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published');
        $data['urutan'] = $data['urutan'] ?? 0;

        return $data;
    }
}
