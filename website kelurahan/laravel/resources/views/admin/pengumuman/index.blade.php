@extends('layouts.admin')
@php $pageTitle = $canManageKonten === true ? 'Kelola Pengumuman' : 'Daftar Pengumuman'; $breadcrumb = ['Pengumuman' => null]; @endphp
@section('title', 'Pengumuman')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-600">{{ $canManageKonten === true ? 'Daftar pengumuman resmi' : 'Lihat pengumuman resmi (hanya baca)' }}</p>
    @if($canManageKonten === true)
    <a href="{{ route('admin.pengumuman.create') }}" class="btn-primary">+ Tambah Pengumuman</a>
    @endif
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <table class="w-full text-sm">
        <thead class="bg-kwamki-sand text-left text-gray-600">
            <tr>
                <th class="px-4 py-3">Judul</th>
                <th class="px-4 py-3">Periode</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pengumumans as $item)
            <tr>
                <td class="px-4 py-3 font-medium">{{ $item->judul }}</td>
                <td class="px-4 py-3 text-gray-500 text-xs">
                    @if($item->tanggal_mulai){{ $item->tanggal_mulai->format('d/m/Y') }}@endif
                    @if($item->tanggal_akhir) — {{ $item->tanggal_akhir->format('d/m/Y') }}@endif
                </td>
                <td class="px-4 py-3"><x-admin.badge :label="$item->is_published ? 'Publik' : 'Draft'" :tone="$item->is_published ? 'success' : 'neutral'" /></td>
                <td class="px-4 py-3 text-right">
                    @if($canManageKonten === true)
                        <x-admin.table-actions
                            :show-route="route('admin.pengumuman.show', $item)"
                            :edit-route="route('admin.pengumuman.edit', $item)"
                            :destroy-route="route('admin.pengumuman.destroy', $item)"
                            item-name="pengumuman" />
                    @else
                        <div class="inline-flex flex-col items-end">
                            <a href="{{ route('admin.pengumuman.show', $item) }}"
                               class="rounded bg-kwamki-sand px-2.5 py-1 text-xs font-medium text-kwamki-forest hover:bg-kwamki-gold/30">
                                Detail
                            </a>
                        </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500">Belum ada pengumuman.</td></tr>
            @endforelse
        </tbody>
    </table>
    @if($pengumumans->hasPages())<div class="border-t px-4 py-3">{{ $pengumumans->links() }}</div>@endif
</div>
@endsection
