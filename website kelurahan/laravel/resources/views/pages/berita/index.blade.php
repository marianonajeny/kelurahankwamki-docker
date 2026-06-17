@extends('layouts.app')

@section('title', 'Berita')
@section('meta_description', 'Berita dan informasi terkini Kelurahan Kwamki.')

@php $breadcrumb = ['Berita' => null]; @endphp

@section('content')
    <x-page-header title="Berita Kelurahan" subtitle="Informasi kegiatan dan perkembangan terkini" />

    <x-page-content-section variant="news">
        <div class="reveal">
            <h2 class="section-title section-title-accent">Semua Berita</h2>
            <p class="mt-2 text-gray-600">Informasi kegiatan dan perkembangan kelurahan</p>
        </div>
        @if($beritas->isEmpty())
            <p class="mt-10 text-center text-gray-500">Belum ada berita yang dipublikasikan.</p>
        @else
            <div class="reveal-stagger mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($beritas as $berita)
                    <x-card-berita :berita="$berita" />
                @endforeach
            </div>
            <div class="mt-10">{{ $beritas->links() }}</div>
        @endif
    </x-page-content-section>
@endsection
