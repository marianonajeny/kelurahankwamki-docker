@extends('layouts.app')

@section('title', 'Layanan Publik')
@section('meta_description', 'Katalog layanan administrasi Kelurahan Kwamki: persyaratan, tarif, jangka waktu, dan pengajuan online.')

@php
    $breadcrumb = ['Layanan' => null];
    $katLabels = \App\Models\Layanan::kategoriLabels();
@endphp

@section('content')
    <x-page-header title="Layanan Publik" subtitle="Transparansi persyaratan dan alur pelayanan publik." />

    <section class="border-b border-gray-100 bg-kwamki-cream/40 py-3">
        <div class="mx-auto max-w-7xl px-4 text-sm text-gray-700 sm:px-6 lg:px-8">
            <strong>Jam layanan:</strong> {{ $jamLayanan }}
        </div>
    </section>

    <x-page-content-section variant="warm" padding="py-10">
        <div class="reveal">
            <h2 class="section-title section-title-accent">Daftar Layanan</h2>
            <p class="mt-2 text-kwamki-teal/80">Akses layanan administrasi kelurahan secara mudah</p>
        </div>
        <div class="reveal mt-8">
            <p class="text-sm text-gray-600">Filter kategori:</p>
            <div class="mt-3 flex flex-wrap gap-2">
                <a href="{{ route('layanan') }}"
                   class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ ($filterKategori ?? null) === null || $filterKategori === '' ? 'bg-kwamki-forest text-white' : 'bg-white text-gray-700 ring-1 ring-gray-200 hover:ring-kwamki-gold' }}">
                    Semua
                </a>
                @foreach($kategoris as $kat)
                    <a href="{{ route('layanan', ['kategori' => $kat]) }}"
                       class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ ($filterKategori ?? null) === $kat ? 'bg-kwamki-forest text-white' : 'bg-white text-gray-700 ring-1 ring-gray-200 hover:ring-kwamki-gold' }}">
                        {{ $katLabels[$kat] ?? ucfirst($kat) }}
                    </a>
                @endforeach
            </div>
        </div>
        <div class="reveal-stagger mt-10 grid gap-6 md:grid-cols-2">
            @forelse($layanans as $layanan)
                <x-layanan-card :layanan="$layanan" />
            @empty
                <p class="col-span-2 text-center text-gray-500">Tidak ada layanan dalam kategori ini atau data sedang diperbarui.</p>
            @endforelse
        </div>
    </x-page-content-section>
@endsection
