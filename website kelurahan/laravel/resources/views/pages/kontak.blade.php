@extends('layouts.app')

@section('title', 'Kontak')
@section('meta_description', 'Hubungi Kelurahan Kwamki — alamat, telepon, dan formulir pesan.')

@php
    $breadcrumb = ['Kontak' => null];
    $settings = \App\Models\Pengaturan::allCached();
@endphp

@section('content')
    <x-page-header title="Hubungi Kami" subtitle="Kami siap melayani pertanyaan dan aspirasi warga" />

    <x-page-content-section variant="warm">
        <div class="grid gap-10 lg:grid-cols-2">
            <div class="reveal-stagger space-y-6">
                <div class="reveal card card-accent p-6">
                    <h2 class="section-title section-title-accent text-lg">Alamat Kantor</h2>
                    <p class="mt-4 text-gray-600">{{ $settings['alamat'] ?? 'Jl. Kwamki, Distrik Mimika Baru, Kab. Mimika, Papua Tengah 99952' }}</p>
                </div>
                <div class="reveal card card-accent p-6">
                    <h2 class="section-title section-title-accent text-lg">Informasi Kontak</h2>
                    <ul class="mt-4 space-y-2 text-gray-600">
                        <li><strong>Telepon:</strong> {{ $settings['telepon'] ?? '-' }}</li>
                        <li><strong>Email:</strong> {{ $settings['email'] ?? 'info@kelurahankwamki.my.id' }}</li>
                        <li><strong>Jam Layanan:</strong> {{ $settings['jam_layanan'] ?? 'Senin–Jumat, 08.00–16.00 WIT' }}</li>
                    </ul>
                </div>
            </div>

            <div class="reveal card p-8">
                <h2 class="section-title section-title-accent text-xl">Kirim Pesan</h2>
                @if($errors->any())
                <ul class="mt-4 rounded-lg bg-red-50 p-4 text-sm text-red-700">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @endif
                <form action="{{ route('kontak.store') }}" method="POST" class="mt-6 space-y-4">
                    @csrf
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                               class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-kwamki-forest focus:ring-kwamki-forest">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-kwamki-forest focus:ring-kwamki-forest">
                        </div>
                        <div>
                            <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                            <input type="text" name="telepon" id="telepon" value="{{ old('telepon') }}"
                                   class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-kwamki-forest focus:ring-kwamki-forest">
                        </div>
                    </div>
                    <div>
                        <label for="subjek" class="block text-sm font-medium text-gray-700">Subjek</label>
                        <input type="text" name="subjek" id="subjek" value="{{ old('subjek') }}" required
                               class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-kwamki-forest focus:ring-kwamki-forest">
                    </div>
                    <div>
                        <label for="pesan" class="block text-sm font-medium text-gray-700">Pesan</label>
                        <textarea name="pesan" id="pesan" rows="5" required
                                  class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-kwamki-forest focus:ring-kwamki-forest">{{ old('pesan') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full sm:w-auto">Kirim Pesan</button>
                </form>
            </div>
        </div>
    </x-page-content-section>
@endsection
