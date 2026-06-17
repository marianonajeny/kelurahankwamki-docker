@extends('layouts.admin')
@php
    use App\Models\Layanan;
    $katLabels = Layanan::kategoriLabels();
    $pageTitle = 'Kelola Layanan';
    $breadcrumb = ['Layanan' => null];
@endphp
@section('title', 'Layanan')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <p class="text-sm text-gray-600">Layanan publik dan standar pelayanan di website</p>
    <a href="{{ route('admin.layanan.create') }}" class="btn-primary">+ Tambah layanan</a>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <div class="overflow-x-auto">
    <table class="w-full min-w-[900px] text-sm">
        <thead class="bg-kwamki-sand text-left text-gray-600">
            <tr>
                <th class="px-4 py-3">Nama</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Slug</th>
                <th class="px-4 py-3">Estimasi</th>
                <th class="px-4 py-3">Ajuan baru</th>
                <th class="px-4 py-3">Status</th>
                <th class="px-4 py-3 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($layanans as $item)
            <tr class="hover:bg-gray-50/80">
                <td class="px-4 py-3 font-medium">
                    {{ $item->nama }}
                    @if($item->menerima_permohonan_online)
                        <span class="ml-1 text-xs font-normal text-kwamki-ocean">[online]</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-gray-600">{{ $katLabels[$item->kategori] ?? $item->kategori }}</td>
                <td class="px-4 py-3 font-mono text-xs text-gray-500">{{ $item->slug }}</td>
                <td class="px-4 py-3 text-gray-600">{{ \Illuminate\Support\Str::limit($item->estimasi_waktu, 24) }}</td>
                <td class="px-4 py-3">
                    @if($item->permohonan_baru_count > 0)
                        <span class="badge-warning">{{ $item->permohonan_baru_count }}</span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-4 py-3"><x-admin.badge :label="$item->is_active ? 'Aktif' : 'Nonaktif'" :tone="$item->is_active ? 'success' : 'neutral'" /></td>
                <td class="px-4 py-3 text-right">
                    <a href="{{ $item->publicUrl() }}" target="_blank" class="mr-3 text-xs font-semibold text-kwamki-ocean hover:underline">Lihat</a>
                    <x-admin.table-actions :edit-route="route('admin.layanan.edit', $item->id)" :destroy-route="route('admin.layanan.destroy', $item->id)" item-name="layanan" />
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Belum ada layanan.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
    @if($layanans->hasPages())<div class="border-t px-4 py-3">{{ $layanans->links() }}</div>@endif
</div>
@endsection
