@extends('layouts.app')

@section('title', 'Permohonan terkirim')
@section('meta_description', 'Konfirmasi pengajuan permohonan layanan Kelurahan Kwamki.')

@php $breadcrumb = ['Layanan' => route('layanan'), 'Permohonan' => null]; @endphp

@section('content')
    <x-page-header title="Permohonan Terkirim" subtitle="Simpan nomor permohonan Anda untuk melacak status" />

    <x-page-content-section variant="warm" maxWidth="max-w-lg" padding="py-20">
        <div class="page-content-enter text-center">
            <div class="hero-fade-in hero-delay-1 mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h1 class="hero-fade-in hero-delay-2 mt-6 text-2xl font-bold text-kwamki-forest">Permohonan berhasil dikirim</h1>
            <p class="hero-fade-in hero-delay-3 mt-3 text-gray-600">Simpan nomor permohonan ini untuk melacak status melalui halaman "Cek status permohonan".</p>
            <div class="hero-fade-in hero-delay-3 mt-8 card card-accent p-6">
                <p class="text-xs uppercase text-gray-500">Nomor permohonan</p>
                <p class="mt-2 font-mono text-3xl font-bold tracking-tight text-kwamki-forest">{{ $nomor }}</p>
            </div>
            <div class="hero-fade-in hero-delay-4 mt-10 flex flex-col gap-3 sm:flex-row sm:justify-center">
                <a href="{{ route('layanan.cek-status') }}" class="btn-primary">Cek status</a>
                <a href="{{ route('layanan') }}" class="btn-secondary border-kwamki-green text-kwamki-green hover:bg-kwamki-green-light">Ke daftar layanan</a>
            </div>
        </div>
    </x-page-content-section>
@endsection
