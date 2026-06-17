@extends('layouts.app')

@section('title', 'Pengumuman')
@section('meta_description', 'Pengumuman resmi Kelurahan Kwamki.')

@php $breadcrumb = ['Pengumuman' => null]; @endphp

@section('content')
    <x-page-header title="Pengumuman" subtitle="Informasi resmi dan pengumuman penting bagi warga" />

    <x-page-content-section variant="alt" maxWidth="max-w-3xl">
        <div class="reveal-stagger space-y-6">
        @forelse($pengumumans as $p)
        <article class="reveal announcement-card card p-6">
            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                <span class="announcement-date">Pengumuman</span>
                <time datetime="{{ $p->created_at->toDateString() }}">{{ $p->created_at->translatedFormat('d F Y') }}</time>
                @if($p->tanggal_akhir)
                <span>— Berlaku s/d {{ $p->tanggal_akhir->translatedFormat('d M Y') }}</span>
                @endif
            </div>
            <h2 class="mt-3 text-xl font-bold text-kwamki-green">{{ $p->judul }}</h2>
            <div class="mt-3 text-gray-700 leading-relaxed">{!! nl2br(e($p->isi)) !!}</div>
        </article>
        @empty
        <p class="text-center text-gray-500">Belum ada pengumuman.</p>
        @endforelse

        @if($pengumumans->hasPages())
        <div class="pt-4">{{ $pengumumans->links() }}</div>
        @endif
        </div>
    </x-page-content-section>
@endsection
