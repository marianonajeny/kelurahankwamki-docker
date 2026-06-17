@extends('layouts.app')

@section('title', 'Ajukan — '.$layanan->nama)
@section('meta_description', 'Formulir permohonan online '.$layanan->nama.' — Kelurahan Kwamki.')

@php
    $katLabels = \App\Models\Layanan::kategoriLabels();
    $katLabel = $katLabels[$layanan->kategori] ?? ucfirst($layanan->kategori);
    $breadcrumb = [
        'Layanan' => route('layanan'),
        $layanan->nama => route('layanan.show', $layanan),
        'Ajukan' => null,
    ];
@endphp

@section('content')
    <x-page-header :title="$layanan->nama" subtitle="Formulir permohonan" />

    <section class="border-b border-gray-100 bg-kwamki-cream/40 py-3">
        <div class="mx-auto max-w-7xl px-4 text-sm text-gray-700 sm:px-6 lg:px-8">
            <strong>Jam layanan:</strong> {{ $jamLayanan }}
        </div>
    </section>

    <x-page-content-section variant="warm" padding="py-12">
        <div class="grid gap-10 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="reveal card card-accent p-6">
                    <p class="text-sm text-gray-600">Kategori: <strong>{{ $katLabel }}</strong></p>
                    <p class="mt-2 text-sm text-gray-600">Isi formulir dengan data yang benar. Anda akan mendapat nomor untuk melacak status permohonan.</p>

                    @if($errors->any())
                        <div class="mt-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                            <ul class="list-disc pl-4">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-6">
                        <x-permohonan-layanan-form :layanan="$layanan" />
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <div class="reveal card card-accent p-6">
                    <h3 class="font-bold text-kwamki-green">Ringkas layanan</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div>
                            <dt class="text-gray-500">Estimasi</dt>
                            <dd class="font-medium text-gray-900">{{ $layanan->estimasi_waktu }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Biaya</dt>
                            <dd class="font-medium text-gray-900">{{ $layanan->biaya }}</dd>
                        </div>
                    </dl>
                    <a href="{{ route('layanan.show', $layanan) }}" class="link-teal mt-4 block text-center text-sm font-semibold">
                        &larr; Detail &amp; persyaratan
                    </a>
                </div>
            </aside>
        </div>
    </x-page-content-section>
@endsection
