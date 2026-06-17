<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BeritaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('admin.only', except: ['index', 'show']),
        ];
    }

    public function index(Request $request): View
    {
        $beritas = Berita::query()->latest('published_at')->paginate(15);
        $canManageKonten = $request->user()?->isAdmin() === true;

        return view('admin.berita.index', compact('beritas', 'canManageKonten'));
    }

    public function show(Berita $beritum): View
    {
        return view('admin.berita.show', ['berita' => $beritum]);
    }

    public function create(): View
    {
        return view('admin.berita.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        Berita::create($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(Berita $beritum): View
    {
        return view('admin.berita.edit', ['berita' => $beritum]);
    }

    public function update(Request $request, Berita $beritum): RedirectResponse
    {
        $data = $this->validateData($request, $beritum->id);
        $beritum->update($data);

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(Berita $beritum): RedirectResponse
    {
        $beritum->delete();

        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'ringkasan' => ['nullable', 'string'],
            'isi' => ['required', 'string'],
            'gambar' => ['nullable', 'image', 'max:2048'],
            'published_at' => ['nullable', 'date'],
            'is_published' => ['boolean'],
        ]);

        $data['is_published'] = $request->boolean('is_published');
        $baseSlug = $data['slug'] ?: Str::slug($data['judul']);
        $slug = $baseSlug;
        $counter = 1;
        while (Berita::query()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }
        $data['slug'] = $slug;

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('berita', 'public');
        } else {
            unset($data['gambar']);
        }

        return $data;
    }
}
