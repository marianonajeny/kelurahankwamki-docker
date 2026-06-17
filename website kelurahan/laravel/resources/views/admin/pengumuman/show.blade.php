@extends('layouts.admin')
@php
    $pageTitle = 'Detail Pengumuman';
    $breadcrumb = ['Pengumuman' => route('admin.pengumuman.index'), $pengumuman->judul => null];
@endphp
@section('title', 'Detail Pengumuman')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="rounded-xl bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-kwamki-forest-dark">{{ $pengumuman->judul }}</h2>
                <p class="mt-2 text-sm text-gray-500">
                    Dibuat: {{ $pengumuman->created_at->translatedFormat('l, d F Y') }}
                </p>
                @if($pengumuman->tanggal_mulai || $pengumuman->tanggal_akhir)
                <p class="mt-1 text-sm text-gray-500">
                    Periode:
                    @if($pengumuman->tanggal_mulai){{ $pengumuman->tanggal_mulai->translatedFormat('d F Y') }}@endif
                    @if($pengumuman->tanggal_akhir) — {{ $pengumuman->tanggal_akhir->translatedFormat('d F Y') }}@endif
                </p>
                @endif
            </div>
            <x-admin.badge :label="$pengumuman->is_published ? 'Publik' : 'Draft'" :tone="$pengumuman->is_published ? 'success' : 'neutral'" />
        </div>

        <div class="mt-6">
            <h3 class="text-sm font-semibold text-gray-700">Isi pengumuman</h3>
            <div class="prose prose-sm mt-2 max-w-none text-gray-700">
                {!! nl2br(e($pengumuman->isi)) !!}
            </div>
        </div>

        @if(filled($pengumuman->file_lampiran))
        <div class="mt-6">
            <h3 class="text-sm font-semibold text-gray-700">Lampiran</h3>
            <a href="{{ asset('storage/'.$pengumuman->file_lampiran) }}" target="_blank" rel="noopener noreferrer"
               class="mt-2 inline-flex text-sm font-medium text-kwamki-ocean hover:underline">
                Unduh lampiran
            </a>
        </div>
        @endif
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.pengumuman.index') }}" class="btn-secondary">Kembali</a>
    </div>
</div>
@endsection
