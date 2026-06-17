<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LayananRequest;
use App\Models\Layanan;
use Illuminate\Support\Str;

class LayananController extends Controller
{
    public function index()
    {
        $layanans = Layanan::query()
            ->withCount(['permohonan as permohonan_baru_count' => function ($q) {
                $q->where('status', \App\Models\PermohonanLayanan::STATUS_DIAJUKAN);
            }])
            ->ordered()
            ->paginate(15);

        return view('admin.layanan.index', compact('layanans'));
    }

    public function create()
    {
        return view('admin.layanan.create');
    }

    public function store(LayananRequest $request)
    {
        Layanan::create($this->prepareData($request, null));

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(Layanan $layanan)
    {
        return view('admin.layanan.edit', compact('layanan'));
    }

    public function update(LayananRequest $request, Layanan $layanan)
    {
        $layanan->update($this->prepareData($request, $layanan->id));

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil diperbarui.');
    }

    public function destroy(Layanan $layanan)
    {
        $layanan->delete();

        return redirect()->route('admin.layanan.index')->with('success', 'Layanan berhasil dihapus.');
    }

    private function prepareData(LayananRequest $request, ?int $ignoreId): array
    {
        $data = $request->validated();
        $data['is_active'] = $request->boolean('is_active');
        $data['menerima_permohonan_online'] = $request->boolean('menerima_permohonan_online');
        $data['urutan'] = $data['urutan'] ?? 0;
        $data['ikon'] = $data['ikon'] ?? 'document';
        $baseSlug = $data['slug'] ?? Str::slug($data['nama']);
        $data['slug'] = $this->makeUniqueSlug($baseSlug, $ignoreId);

        return $data;
    }

    private function makeUniqueSlug(string $slug, ?int $ignoreId = null): string
    {
        $original = $slug ?: 'layanan';
        $slug = $original;
        $counter = 1;

        while (Layanan::query()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $original.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
