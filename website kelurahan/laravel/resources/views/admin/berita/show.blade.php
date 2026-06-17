@extends('layouts.admin')
@php
    $pageTitle = 'Detail Berita';
    $breadcrumb = ['Berita' => route('admin.berita.index'), $berita->judul => null];
@endphp
@section('title', 'Detail Berita')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="rounded-xl bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-kwamki-forest-dark">{{ $berita->judul }}</h2>
                <p class="mt-2 text-sm text-gray-500">
                    @if($berita->published_at)
                        Terbit: {{ $berita->published_at->translatedFormat('l, d F Y H:i') }}
                    @else
                        Belum dijadwalkan terbit
                    @endif
                </p>
            </div>
            <x-admin.badge :label="$berita->is_published ? 'Publik' : 'Draft'" :tone="$berita->is_published ? 'success' : 'neutral'" />
        </div>

        @if($berita->gambar)
        <img src="{{ asset('storage/'.$berita->gambar) }}" alt="{{ $berita->judul }}"
             class="mt-6 w-full max-h-80 rounded-lg object-cover">
        @endif

        @if(filled($berita->ringkasan))
        <div class="mt-6">
            <h3 class="text-sm font-semibold text-gray-700">Ringkasan</h3>
            <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $berita->ringkasan }}</p>
        </div>
        @endif

        <div class="mt-6">
            <h3 class="text-sm font-semibold text-gray-700">Isi berita</h3>
            <div class="prose prose-sm mt-2 max-w-none text-gray-700">
                {!! nl2br(e($berita->isi)) !!}
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.berita.index') }}" class="btn-secondary">Kembali</a>
        @if($berita->is_published)
        <a href="{{ route('berita.show', $berita) }}" target="_blank" rel="noopener noreferrer"
           class="rounded-lg border border-kwamki-ocean px-4 py-2 text-sm font-medium text-kwamki-ocean hover:bg-kwamki-ocean/5">
            Lihat di website
        </a>
        @endif
    </div>
</div>
@endsection
