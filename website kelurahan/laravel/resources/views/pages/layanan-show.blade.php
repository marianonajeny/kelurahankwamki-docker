@extends('layouts.app')

@section('title', $layanan->nama.' — Layanan')
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($layanan->deskripsi ?? ''), 155))

@php
    $katLabels = \App\Models\Layanan::kategoriLabels();
    $katLabel = $katLabels[$layanan->kategori] ?? ucfirst($layanan->kategori);
    $breadcrumb = [
        'Layanan' => route('layanan'),
        $layanan->nama => null,
    ];
    $syaratLines = collect(preg_split('/\r\n|\r|\n/', $layanan->persyaratan ?? ''))->map(fn ($l) => trim($l))->filter()->values();
    $alurLines = collect(preg_split('/\r\n|\r|\n/', $layanan->alur ?? ''))->map(fn ($l) => trim($l))->filter()->values();
@endphp

@section('content')
    <x-page-header :title="$layanan->nama" :subtitle="'Kategori: '.$katLabel" />

    <section class="border-b border-gray-100 bg-kwamki-cream/40 py-3">
        <div class="mx-auto max-w-7xl px-4 text-sm text-gray-700 sm:px-6 lg:px-8">
            <strong>Jam layanan:</strong> {{ $jamLayanan }}
        </div>
    </section>

    <x-page-content-section variant="warm" padding="py-12">
        <div class="grid gap-10 lg:grid-cols-3">
            <div class="reveal-stagger lg:col-span-2 space-y-10">
                @if($layanan->deskripsi)
                    <div class="reveal card card-accent p-6">
                        <h2 class="text-lg font-bold text-kwamki-green">Ringkasan</h2>
                        <p class="mt-3 text-gray-700 whitespace-pre-line">{{ $layanan->deskripsi }}</p>
                    </div>
                @endif

                <div class="reveal card card-accent p-6">
                    <h2 class="text-lg font-bold text-kwamki-green">A. Persyaratan</h2>
                    @if($syaratLines->isEmpty())
                        <p class="mt-3 text-sm text-gray-500">Persyaratan akan diperbarui. Silakan hubungi loket pelayanan.</p>
                    @else
                        <ul class="mt-4 list-disc space-y-2 pl-5 text-gray-700">
                            @foreach($syaratLines as $line)
                                <li>{{ ltrim($line, '-• ') }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="reveal card card-accent p-6">
                    <h2 class="text-lg font-bold text-kwamki-green">B. Tarif / biaya</h2>
                    <p class="mt-3 text-lg font-semibold text-kwamki-forest">{{ $layanan->biaya }}</p>
                </div>

                <div class="reveal card card-accent p-6">
                    <h2 class="text-lg font-bold text-kwamki-green">C. Jangka waktu</h2>
                    <p class="mt-3 text-gray-700">{{ $layanan->estimasi_waktu }} sejak berkas lengkap (kecuali diatur lain oleh peraturan setempat).</p>
                </div>

                <div class="reveal card card-accent p-6">
                    <h2 class="text-lg font-bold text-kwamki-green">D. Prosedur / alur</h2>
                    @if($alurLines->isEmpty())
                        <p class="mt-3 text-sm text-gray-500">Alur pelayanan: datang ke loket dengan persyaratan lengkap — petugas akan memverifikasi dan memproses permohonan Anda.</p>
                    @else
                        <ol class="mt-4 list-decimal space-y-2 pl-5 text-gray-700">
                            @foreach($alurLines as $line)
                                <li>{{ ltrim($line, '-• ') }}</li>
                            @endforeach
                        </ol>
                    @endif
                </div>

                @if($layanan->petugas || $layanan->lokasi)
                    <div class="reveal card card-accent p-6">
                        <h2 class="text-lg font-bold text-kwamki-green">Petugas & lokasi</h2>
                        <ul class="mt-3 space-y-2 text-gray-700 text-sm">
                            @if($layanan->petugas)
                                <li><strong>Unit / penanggung jawab:</strong> {{ $layanan->petugas }}</li>
                            @endif
                            @if($layanan->lokasi)
                                <li><strong>Lokasi layanan:</strong> {{ $layanan->lokasi }}</li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>

            <aside class="reveal-stagger space-y-6">
                <div class="reveal card card-accent p-6">
                    <h3 class="font-bold text-kwamki-green">Ringkas</h3>
                    <dl class="mt-4 space-y-3 text-sm">
                        <div>
                            <dt class="text-gray-500">Estimasi</dt>
                            <dd class="font-medium text-gray-900">{{ $layanan->estimasi_waktu }}</dd>
                        </div>
                        <div>
                            <dt class="text-gray-500">Biaya</dt>
                            <dd class="font-medium text-gray-900">{{ $layanan->biaya }}</dd>
                        </div>
                        @if($layanan->dokumen_url)
                            <div>
                                <dt class="text-gray-500">Formulir</dt>
                                <dd><a href="{{ $layanan->dokumen_url }}" class="link-teal font-semibold" target="_blank" rel="noopener">Unduh / buka formulir</a></dd>
                            </div>
                        @endif
                    </dl>

                    @if($layanan->link_url)
                        <a href="{{ $layanan->link_url }}" class="mt-4 block w-full rounded-lg bg-kwamki-ocean py-3 text-center text-sm font-semibold text-white hover:bg-kwamki-forest" target="_blank" rel="noopener">Buka tautan terkait</a>
                    @endif

                    @if($wa)
                        <a href="https://wa.me/{{ preg_replace('/\D/', '', $wa) }}" class="mt-3 block w-full rounded-lg border-2 border-kwamki-gold py-3 text-center text-sm font-semibold text-kwamki-forest hover:bg-kwamki-gold/10" target="_blank" rel="noopener">WhatsApp kantor</a>
                    @endif

                    <a href="{{ route('kontak') }}" class="link-teal mt-3 block text-center text-sm font-semibold">Halaman kontak</a>
                </div>

                @if($layanan->menerima_permohonan_online)
                    <div class="reveal card card-accent p-6">
                        <h3 class="font-bold text-kwamki-green">Pengajuan online</h3>
                        <p class="mt-2 text-sm text-gray-600">Ajukan permohonan melalui formulir online. Pastikan berkas sesuai persyaratan.</p>
                        <a href="{{ $layanan->publicAjukanUrl() }}" class="btn-primary mt-4 block w-full text-center">
                            Ajukan Online
                        </a>
                    </div>
                @else
                    <div class="reveal card border border-kwamki-gold/40 bg-kwamki-gold/5 p-6">
                        <h3 class="font-bold text-kwamki-green">Pengajuan</h3>
                        <p class="mt-2 text-sm text-gray-700">Layanan ini ditangani melalui datang langsung atau saluran lain. Gunakan WhatsApp atau halaman kontak.</p>
                    </div>
                @endif
            </aside>
        </div>
    </x-page-content-section>
@endsection
