@extends('layouts.admin')
@php $pageTitle = $canManageKonten === true ? 'Kelola Berita' : 'Daftar Berita'; $breadcrumb = ['Berita' => null]; @endphp
@section('title', 'Berita')

@section('content')
<div class="mb-6 flex items-center justify-between">
    @if($canManageKonten === true)
        <p class="text-sm text-gray-600">Daftar berita website kelurahan</p>
        <a href="{{ route('admin.berita.create') }}" class="btn-primary">+ Tambah Berita</a>
    @else
        <p class="text-sm text-gray-600">Lihat berita website kelurahan (hanya baca)</p>
    @endif
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-kwamki-sand text-left text-gray-600">
            <tr>
                <th class="px-4 py-3">Judul</th>
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($beritas as $berita)
            <tr>
                <td class="px-4 py-3 font-medium text-kwamki-forest">{{ $berita->judul }}</td>
                <td class="px-4 py-3 text-gray-500">{{ $berita->published_at?->format('d/m/Y H:i') }}</td>
                <td class="px-4 py-3">
                    <x-admin.badge :label="$berita->is_published ? 'Publik' : 'Draft'" :tone="$berita->is_published ? 'success' : 'neutral'" />
                </td>
                <td class="px-4 py-3 text-right">
                    @if($canManageKonten === true)
                        <x-admin.table-actions
                            :show-route="route('admin.berita.show', $berita)"
                            :edit-route="route('admin.berita.edit', $berita)"
                            :destroy-route="route('admin.berita.destroy', $berita)"
                            item-name="berita" />
                    @else
                        <div class="inline-flex flex-col items-end">
                            <a href="{{ route('admin.berita.show', $berita) }}"
                               class="rounded bg-kwamki-sand px-2.5 py-1 text-xs font-medium text-kwamki-forest hover:bg-kwamki-gold/30">
                                Detail
                            </a>
                        </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada berita.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($beritas->hasPages())
    <div class="border-t px-4 py-3">{{ $beritas->links() }}</div>
    @endif
</div>
@endsection
