@extends('layouts.app')

@section('title', 'Galeri')
@section('meta_description', 'Galeri foto kegiatan dan dokumentasi Kelurahan Kwamki.')

@php $breadcrumb = ['Galeri' => null]; @endphp

@section('content')
    <x-page-header title="Galeri Kegiatan" subtitle="Dokumentasi kegiatan, pelayanan, dan momen penting di Kelurahan Kwamki" />

    <x-page-content-section variant="alt">
        @if($galeris->isEmpty())
            <p class="text-center text-gray-500">Belum ada foto galeri yang dipublikasikan.</p>
        @else
            @if($kategoris->isNotEmpty())
            <div class="reveal mb-8 flex flex-wrap justify-center gap-2" role="tablist" aria-label="Filter kategori galeri">
                <button type="button"
                        data-galeri-filter="all"
                        class="galeri-filter-btn rounded-full bg-kwamki-forest px-4 py-1.5 text-sm font-semibold text-white"
                        aria-pressed="true">
                    Semua
                </button>
                @foreach($kategoris as $kat)
                <button type="button"
                        data-galeri-filter="{{ $kat }}"
                        class="galeri-filter-btn rounded-full border border-gray-300 bg-white px-4 py-1.5 text-sm font-medium text-gray-700 hover:border-kwamki-forest hover:text-kwamki-forest"
                        aria-pressed="false">
                    {{ ucfirst($kat) }}
                </button>
                @endforeach
            </div>
            @endif

            <div id="galeri-grid" class="reveal-stagger grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($galeris as $g)
                @php
                    $imgUrl = $g->gambar ? asset('storage/'.$g->gambar) : $sitePlaceholderImage;
                @endphp
                <article class="galeri-item reveal card card-interactive overflow-hidden" data-kategori="{{ $g->kategori }}">
                    <a href="#"
                       data-lightbox="{{ $imgUrl }}"
                       class="block aspect-square overflow-hidden bg-gray-100 ring-2 ring-transparent transition hover:ring-kwamki-teal/40"
                       aria-label="Perbesar: {{ $g->judul }}">
                        <img src="{{ $imgUrl }}"
                             alt="{{ $g->judul }}"
                             class="h-full w-full object-cover transition duration-300 hover:scale-110"
                             loading="lazy">
                    </a>
                    <div class="p-3">
                        <h2 class="font-semibold text-kwamki-green line-clamp-2">{{ $g->judul }}</h2>
                        <span class="mt-1 inline-block rounded-full bg-kwamki-sand px-2 py-0.5 text-xs font-medium capitalize text-kwamki-forest">
                            {{ $g->kategori }}
                        </span>
                    </div>
                </article>
                @endforeach
            </div>

            <p id="galeri-empty-filter" class="hidden text-center text-gray-500">Tidak ada foto untuk kategori ini.</p>
        @endif
    </x-page-content-section>
@endsection

@push('head')
@if($galeris->isNotEmpty() && $kategoris->isNotEmpty())
<script>
document.addEventListener('DOMContentLoaded', () => {
    const buttons = document.querySelectorAll('[data-galeri-filter]');
    const items = document.querySelectorAll('.galeri-item');
    const emptyMsg = document.getElementById('galeri-empty-filter');
    if (!buttons.length || !items.length) return;

    const setActive = (btn) => {
        buttons.forEach((b) => {
            const active = b === btn;
            b.setAttribute('aria-pressed', active ? 'true' : 'false');
            b.classList.toggle('bg-kwamki-forest', active);
            b.classList.toggle('text-white', active);
            b.classList.toggle('border', !active);
            b.classList.toggle('border-gray-300', !active);
            b.classList.toggle('bg-white', !active);
            b.classList.toggle('text-gray-700', !active);
        });
    };

    buttons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const filter = btn.dataset.galeriFilter;
            setActive(btn);
            let visible = 0;
            items.forEach((item) => {
                const show = filter === 'all' || item.dataset.kategori === filter;
                item.classList.toggle('hidden', !show);
                if (show) visible++;
            });
            emptyMsg?.classList.toggle('hidden', visible > 0);
        });
    });
});
</script>
@endif
@endpush
