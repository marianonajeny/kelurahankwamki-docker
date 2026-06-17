@extends('layouts.admin')
@php
    $pageTitle = 'Pesan kontak';
    $breadcrumb = ['Pesan' => null];
@endphp
@section('title', 'Pesan')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    <p class="text-sm text-gray-600">Pesan dari formulir kontak website</p>
    <form method="GET" class="flex flex-wrap gap-2">
        <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">Semua pesan</option>
            <option value="belum_dibaca" @selected(request('status') === 'belum_dibaca')>Belum dibaca</option>
        </select>
        @if(request('status'))
            <a href="{{ route('admin.pesan.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50">Reset</a>
        @endif
    </form>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[640px] text-sm">
            <thead class="bg-kwamki-sand text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">Subjek</th>
                    <th class="px-4 py-3">Pengirim</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($pesans as $p)
                <tr class="hover:bg-gray-50/80 {{ !$p->is_read ? 'bg-amber-50/40' : '' }}">
                    <td class="px-4 py-3 font-medium text-kwamki-forest max-w-[200px] truncate" title="{{ $p->subjek }}">{{ $p->subjek }}</td>
                    <td class="px-4 py-3">
                        <p>{{ $p->nama }}</p>
                        <p class="text-xs text-gray-500">{{ $p->email }}</p>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->created_at->translatedFormat('d M Y H:i') }}</td>
                    <td class="px-4 py-3">
                        @if($p->is_read)
                            <x-admin.badge label="Sudah dibaca" tone="neutral" />
                        @else
                            <x-admin.badge label="Belum dibaca" tone="warning" />
                        @endif
                    </td>
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.pesan.show', $p) }}" class="text-sm font-semibold text-kwamki-ocean hover:underline">Detail</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada pesan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($pesans->hasPages())
    <div class="border-t px-4 py-3">{{ $pesans->links() }}</div>
    @endif
</div>
@endsection
