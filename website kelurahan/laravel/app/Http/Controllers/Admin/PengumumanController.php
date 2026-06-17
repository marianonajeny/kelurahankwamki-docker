<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PengumumanRequest;
use App\Models\Pengumuman;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PengumumanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('admin.only', except: ['index', 'show']),
        ];
    }

    public function index()
    {
        $pengumumans = Pengumuman::latest()->paginate(15);

        return view('admin.pengumuman.index', compact('pengumumans'));
    }

    public function show(string $pengumuman)
    {
        $item = Pengumuman::findOrFail($pengumuman);

        return view('admin.pengumuman.show', ['pengumuman' => $item]);
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(PengumumanRequest $request)
    {
        Pengumuman::create($this->prepareData($request));

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit(string $pengumuman)
    {
        $item = Pengumuman::findOrFail($pengumuman);

        return view('admin.pengumuman.edit', ['pengumuman' => $item]);
    }

    public function update(PengumumanRequest $request, string $pengumuman)
    {
        Pengumuman::findOrFail($pengumuman)->update($this->prepareData($request));

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(string $pengumuman)
    {
        Pengumuman::findOrFail($pengumuman)->delete();

        return redirect()->route('admin.pengumuman.index')->with('success', 'Pengumuman berhasil dihapus.');
    }

    private function prepareData(PengumumanRequest $request): array
    {
        $data = $request->validated();
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }
}
