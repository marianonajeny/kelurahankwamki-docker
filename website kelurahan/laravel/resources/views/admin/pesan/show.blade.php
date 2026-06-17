@extends('layouts.admin')
@php
    $pageTitle = 'Detail pesan';
    $breadcrumb = ['Pesan' => route('admin.pesan.index'), $pesan->subjek => null];
@endphp
@section('title', $pesan->subjek)

@section('content')
<div class="mx-auto max-w-4xl space-y-8">
    <div class="flex flex-wrap items-start justify-between gap-4 rounded-xl bg-white p-6 shadow-sm">
        <div>
            <p class="text-xs uppercase text-gray-500">Subjek</p>
            <p class="text-xl font-bold text-kwamki-forest">{{ $pesan->subjek }}</p>
            <p class="mt-2 text-sm text-gray-600">{{ $pesan->created_at->translatedFormat('d F Y H:i') }}</p>
        </div>
        <div>
            @if($pesan->is_read)
                <x-admin.badge label="Sudah dibaca" tone="neutral" />
            @else
                <x-admin.badge label="Belum dibaca" tone="warning" />
            @endif
        </div>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm">
        <h3 class="font-bold text-kwamki-forest">Data pengirim</h3>
        <dl class="mt-4 grid gap-3 text-sm sm:grid-cols-2">
            <div><dt class="text-gray-500">Nama</dt><dd class="font-medium">{{ $pesan->nama }}</dd></div>
            <div><dt class="text-gray-500">Email</dt><dd><a href="mailto:{{ $pesan->email }}" class="text-kwamki-ocean hover:underline">{{ $pesan->email }}</a></dd></div>
            <div><dt class="text-gray-500">Telepon</dt><dd>{{ $pesan->telepon ?: '—' }}</dd></div>
        </dl>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm">
        <h3 class="font-bold text-kwamki-forest">Isi pesan</h3>
        <p class="mt-4 whitespace-pre-line text-sm text-gray-700">{{ $pesan->pesan }}</p>
    </div>

    <div class="flex flex-wrap gap-3">
        <a href="{{ route('admin.pesan.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Kembali ke daftar</a>
        @if($pesan->is_read)
        <form method="POST" action="{{ route('admin.pesan.mark-unread', $pesan) }}">
            @csrf
            @method('PATCH')
            <button type="submit" class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-2 text-sm font-medium text-amber-900 hover:bg-amber-100">Tandai belum dibaca</button>
        </form>
        @endif
    </div>
</div>
@endsection
