@extends('layouts.admin')

@php
    $pageTitle = $isLurah ? 'Antrian verifikasi surat' : 'Permohonan layanan';
    $breadcrumb = ['Permohonan' => null];
@endphp

@section('title', 'Permohonan')

@section('content')
<div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
    @if($isLurah)
        <p class="text-sm text-gray-600">Antrian verifikasi surat dari admin kelurahan.</p>
    @else
        <p class="text-sm text-gray-600">Pengajuan online dari masyarakat</p>
    @endif
    <form method="GET" class="flex flex-wrap items-center gap-2">
        @if($isLurah)
            <x-admin.permohonan-settings-icon />
        @endif
        @if(! $isLurah)
        <select name="layanan_id" class="rounded-lg border border-gray-300 px-3 py-2 text-sm" onchange="this.form.submit()">
            <option value="">Semua layanan</option>
            @foreach($layanans as $l)
                <option value="{{ $l->id }}" @selected(request('layanan_id') == $l->id)>{{ $l->nama }}</option>
            @endforeach
        </select>
        @endif
        <select name="status" class="rounded-lg border border-gray-300 px-3 py-2 text-sm" onchange="this.form.submit()">
            @if($isLurah)
                <option value="menunggu_verifikasi_kepala_kelurahan" @selected(request('status', 'menunggu_verifikasi_kepala_kelurahan') === 'menunggu_verifikasi_kepala_kelurahan')>Menunggu Verifikasi</option>
                <option value="ditandatangani_kepala_kelurahan" @selected(request('status') === 'ditandatangani_kepala_kelurahan')>Ditandatangani</option>
                <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
            @else
                <option value="">Semua status</option>
                @foreach(['diajukan','diproses_admin','perlu_perbaikan_data','terverifikasi_admin','menunggu_verifikasi_kepala_kelurahan','revisi_dari_kepala_kelurahan','ditandatangani_kepala_kelurahan','selesai','ditolak'] as $s)
                    <option value="{{ $s }}" @selected(request('status') === $s)>{{ str_replace('_', ' ', ucfirst($s)) }}</option>
                @endforeach
            @endif
        </select>
        @if(request()->hasAny(['status', 'layanan_id']))
            <a href="{{ route('admin.permohonan.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm hover:bg-gray-50">Reset</a>
        @endif
    </form>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] text-sm">
            <thead class="bg-kwamki-sand text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">Nomor</th>
                    <th class="px-4 py-3">Layanan</th>
                    <th class="px-4 py-3">Pemohon</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Tanggal</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($permohonans as $p)
                <tr class="hover:bg-gray-50/80">
                    <td class="px-4 py-3 font-mono text-xs font-medium">{{ $p->nomor }}</td>
                    <td class="max-w-[180px] truncate px-4 py-3" title="{{ $p->layanan?->nama }}">{{ $p->layanan?->nama }}</td>
                    <td class="px-4 py-3">{{ $p->nama }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$p->status" /></td>
                    <td class="px-4 py-3 text-gray-600">{{ $p->created_at->translatedFormat('d M Y') }}</td>
                    <td class="px-4 py-3 text-right">
                        <x-admin.permohonan-actions :permohonan="$p" redirect="index" />
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Belum ada permohonan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($permohonans->hasPages())
        <div class="border-t border-gray-100 px-4 py-3">{{ $permohonans->links() }}</div>
    @endif
</div>
@endsection
