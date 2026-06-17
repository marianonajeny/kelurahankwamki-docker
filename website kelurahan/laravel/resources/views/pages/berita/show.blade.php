@extends('layouts.app')

@section('title', $berita->judul)
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($berita->ringkasan ?: $berita->isi), 160))

@php
    $breadcrumb = ['Berita' => route('berita.index'), $berita->judul => null];
    $beritaSubtitle = $berita->published_at ? $berita->published_at->translatedFormat('l, d F Y') : null;
@endphp

@section('content')
    <x-page-header :title="$berita->judul" :subtitle="$beritaSubtitle" />

    <x-page-content-section variant="news" maxWidth="max-w-3xl">
        <article>
            <time class="reveal text-sm text-kwamki-gold" datetime="{{ $berita->published_at?->toDateString() }}">
                {{ $berita->published_at?->translatedFormat('l, d F Y') }}
            </time>
            <h1 class="reveal mt-2 text-3xl font-bold text-kwamki-forest md:text-4xl">{{ $berita->judul }}</h1>

            @if($berita->gambar)
            <img src="{{ asset('storage/'.$berita->gambar) }}" alt="{{ $berita->judul }}" class="reveal mt-8 w-full max-h-96 rounded-xl object-cover">
            @endif

            <div class="reveal mt-8 prose prose-lg max-w-none text-gray-700 leading-relaxed">
                {!! nl2br(e($berita->isi)) !!}
            </div>

            @if($beritaTerkait->isNotEmpty())
            <aside class="reveal mt-16 border-t border-kwamki-green/25 pt-10">
                <h2 class="section-title section-title-accent text-xl">Berita Terkait</h2>
                <ul class="mt-4 space-y-3">
                    @foreach($beritaTerkait as $item)
                    <li>
                        <a href="{{ route('berita.show', $item) }}" class="link-teal font-medium">
                            {{ $item->judul }}
                        </a>
                        <span class="text-xs text-gray-500 ml-2">{{ $item->published_at?->translatedFormat('d M Y') }}</span>
                    </li>
                    @endforeach
                </ul>
            </aside>
            @endif
        </article>
    </x-page-content-section>
@endsection
