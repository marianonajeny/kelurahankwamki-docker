@extends('layouts.admin')

@php
    $pageTitle = 'Dashboard';
    $breadcrumb = ['Dashboard' => null];
@endphp

@section('title', 'Dashboard')

@section('content')
<div class="rounded-xl bg-gradient-to-r from-kwamki-forest-dark to-kwamki-forest p-6 text-white shadow-md">
    <h2 class="text-xl font-bold">Selamat datang, {{ auth()->user()->name }}</h2>
    @if($canManageKonten === true)
        <p class="mt-1 text-white/80">Panel administrasi website Kelurahan Kwamki — Kelola konten dan layanan publik.</p>
    @else
        <p class="mt-1 text-white/80">Panel Kepala Kelurahan — Pantau konten website dan antrian verifikasi surat.</p>
    @endif
</div>

<div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 {{ $canManageKonten === true ? 'xl:grid-cols-6' : 'xl:grid-cols-4' }}">
    <x-admin.stat-card label="Total Berita" :value="$stats['berita']" href="{{ route('admin.berita.index') }}" />
    <x-admin.stat-card label="Pengumuman" :value="$stats['pengumuman']" color="ocean" href="{{ route('admin.pengumuman.index') }}" />
    <x-admin.stat-card label="Galeri Foto" :value="$stats['galeri']" color="gold" href="{{ route('admin.galeri.index') }}" />
    @if($canManageKonten === true)
        <x-admin.stat-card label="Layanan Aktif" :value="$stats['layanan']" href="{{ route('admin.layanan.index') }}" />
        <x-admin.stat-card label="Permohonan Baru" :value="$stats['permohonan_baru']" color="ocean" href="{{ route('admin.permohonan.index', ['status' => 'diajukan']) }}" />
        <x-admin.stat-card label="Pesan Baru" :value="$stats['pesan_baru']" color="red" />
    @else
        <x-admin.stat-card label="Antrian Verifikasi" :value="$stats['permohonan_antrian']" color="ocean" href="{{ route('admin.permohonan.index', ['status' => 'menunggu_verifikasi_kepala_kelurahan']) }}" />
    @endif
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <div class="rounded-xl bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-kwamki-forest-dark">Berita Terbaru</h3>
            <a href="{{ route('admin.berita.index') }}" class="text-sm text-kwamki-ocean hover:text-kwamki-gold">Lihat semua</a>
        </div>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b text-left text-gray-500"><th class="pb-2">Judul</th><th class="pb-2">Tanggal</th><th class="pb-2">Status</th></tr></thead>
                <tbody>
                    @forelse($beritaTerbaru as $b)
                    <tr class="border-b border-gray-50">
                        <td class="py-2 pr-2 font-medium text-kwamki-forest line-clamp-1">{{ $b->judul }}</td>
                        <td class="py-2 text-gray-500">{{ $b->published_at?->format('d/m/Y') ?? '-' }}</td>
                        <td class="py-2"><x-admin.badge :label="$b->is_published ? 'Publik' : 'Draft'" :tone="$b->is_published ? 'success' : 'neutral'" /></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="py-4 text-gray-500">Belum ada berita.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm">
        <div class="flex items-center justify-between">
            <h3 class="font-semibold text-kwamki-forest-dark">Pengumuman Terbaru</h3>
            <a href="{{ route('admin.pengumuman.index') }}" class="text-sm text-kwamki-ocean hover:text-kwamki-gold">Lihat semua</a>
        </div>
        <div class="mt-4 overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b text-left text-gray-500"><th class="pb-2">Judul</th><th class="pb-2">Tanggal</th><th class="pb-2">Status</th></tr></thead>
                <tbody>
                    @forelse($pengumumanTerbaru as $p)
                    <tr class="border-b border-gray-50">
                        <td class="py-2 pr-2 font-medium text-kwamki-forest line-clamp-1">{{ $p->judul }}</td>
                        <td class="py-2 text-gray-500">{{ $p->created_at->format('d/m/Y') }}</td>
                        <td class="py-2"><x-admin.badge :label="$p->is_published ? 'Publik' : 'Draft'" :tone="$p->is_published ? 'success' : 'neutral'" /></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="py-4 text-gray-500">Belum ada pengumuman.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-8 rounded-xl bg-white p-6 shadow-sm">
    <div class="flex items-center justify-between">
        <h3 class="font-semibold text-kwamki-forest-dark">Permohonan layanan terbaru</h3>
        <a href="{{ route('admin.permohonan.index') }}" class="text-sm text-kwamki-ocean hover:text-kwamki-gold">Lihat semua</a>
    </div>
    <div class="mt-4 overflow-x-auto">
        <table class="w-full text-sm">
            <thead><tr class="border-b text-left text-gray-500"><th class="pb-2">Nomor</th><th class="pb-2">Layanan</th><th class="pb-2">Pemohon</th><th class="pb-2">Status</th></tr></thead>
            <tbody>
                @forelse($permohonanTerbaru as $pm)
                <tr class="border-b border-gray-50">
                    <td class="py-2 font-mono text-xs">{{ $pm->nomor }}</td>
                    <td class="py-2 pr-2 line-clamp-1">{{ $pm->layanan?->nama }}</td>
                    <td class="py-2">{{ $pm->nama }}</td>
                    <td class="py-2"><x-status-badge :status="$pm->status" /></td>
                </tr>
                @empty
                <tr><td colspan="4" class="py-4 text-gray-500">Belum ada permohonan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($canManageKonten === true && $pesanTerbaru->isNotEmpty())
<div class="mt-6 rounded-xl bg-white p-6 shadow-sm">
    <h3 class="font-semibold text-kwamki-forest-dark">Pesan Masuk Belum Dibaca</h3>
    <ul class="mt-4 divide-y divide-gray-100">
        @foreach($pesanTerbaru as $pesan)
        <li class="py-3">
            <p class="font-medium text-kwamki-forest">{{ $pesan->subjek }}</p>
            <p class="text-sm text-gray-600">{{ $pesan->nama }} — {{ $pesan->email }}</p>
            <p class="mt-1 text-xs text-gray-400">{{ $pesan->created_at->diffForHumans() }}</p>
        </li>
        @endforeach
    </ul>
</div>
@endif
@endsection
