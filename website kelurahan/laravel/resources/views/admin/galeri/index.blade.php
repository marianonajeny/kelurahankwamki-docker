@extends('layouts.admin')
@php $pageTitle = $canManageKonten === true ? 'Kelola Galeri' : 'Daftar Galeri'; $breadcrumb = ['Galeri' => null]; @endphp
@section('title', 'Galeri')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-600">{{ $canManageKonten === true ? 'Foto kegiatan kelurahan' : 'Lihat foto kegiatan kelurahan (hanya baca)' }}</p>
    @if($canManageKonten === true)
    <a href="{{ route('admin.galeri.create') }}" class="btn-primary">+ Tambah Foto</a>
    @endif
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-kwamki-sand text-left text-gray-600">
            <tr>
                <th class="px-4 py-3">Preview</th>
                <th class="px-4 py-3">Judul</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($galeris as $item)
            <tr>
                <td class="px-4 py-3">
                    @if($item->gambar)
                    <img src="{{ asset('storage/'.$item->gambar) }}" alt="" class="h-12 w-12 rounded object-cover">
                    @endif
                </td>
                <td class="px-4 py-3 font-medium">{{ $item->judul }}</td>
                <td class="px-4 py-3 capitalize text-gray-500">{{ $item->kategori }}</td>
                <td class="px-4 py-3"><x-admin.badge :label="$item->is_published ? 'Publik' : 'Draft'" :tone="$item->is_published ? 'success' : 'neutral'" /></td>
                <td class="px-4 py-3 text-right">
                    @if($canManageKonten === true)
                        <x-admin.table-actions
                            :show-route="route('admin.galeri.show', $item)"
                            :edit-route="route('admin.galeri.edit', $item)"
                            :destroy-route="route('admin.galeri.destroy', $item)"
                            item-name="foto" />
                    @else
                        <div class="inline-flex flex-col items-end">
                            <a href="{{ route('admin.galeri.show', $item) }}"
                               class="rounded bg-kwamki-sand px-2.5 py-1 text-xs font-medium text-kwamki-forest hover:bg-kwamki-gold/30">
                                Detail
                            </a>
                        </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500">Belum ada foto.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($galeris->hasPages())<div class="border-t px-4 py-3">{{ $galeris->links() }}</div>@endif
</div>
@endsection
