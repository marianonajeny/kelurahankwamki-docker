@extends('layouts.admin')
@php
    $pageTitle = 'Detail Galeri';
    $breadcrumb = ['Galeri' => route('admin.galeri.index'), $galeri->judul => null];
@endphp
@section('title', 'Detail Galeri')

@section('content')
<div class="mx-auto max-w-3xl space-y-6">
    <div class="rounded-xl bg-white p-6 shadow-sm">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-kwamki-forest-dark">{{ $galeri->judul }}</h2>
                <p class="mt-2 text-sm text-gray-500">
                    Dibuat: {{ $galeri->created_at->translatedFormat('l, d F Y') }}
                </p>
                <p class="mt-1 text-sm text-gray-500">
                    Kategori: <span class="capitalize">{{ $galeri->kategori }}</span>
                    · Urutan: {{ $galeri->urutan }}
                </p>
            </div>
            <x-admin.badge :label="$galeri->is_published ? 'Publik' : 'Draft'" :tone="$galeri->is_published ? 'success' : 'neutral'" />
        </div>

        @if($galeri->gambar)
        <img src="{{ asset('storage/'.$galeri->gambar) }}" alt="{{ $galeri->judul }}"
             class="mt-6 w-full max-h-96 rounded-lg object-cover">
        @endif
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.galeri.index') }}" class="btn-secondary">Kembali</a>
    </div>
</div>
@endsection
