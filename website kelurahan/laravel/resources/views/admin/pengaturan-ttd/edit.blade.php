@extends('layouts.admin')
@php
    $pageTitle = 'Pengaturan TTD Kepala Kelurahan';
    $breadcrumb = ['Permohonan' => route('admin.permohonan.index'), 'Pengaturan TTD' => null];
@endphp
@section('title', 'Pengaturan TTD')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <div class="rounded-lg border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-900">
        <p class="font-semibold">TTD otomatis di semua surat</p>
        <p class="mt-1">Data yang Anda simpan di sini otomatis muncul di pratinjau surat, PDF terbit, dan PDF setelah verifikasi Kepala Kelurahan.</p>
    </div>

    <div class="rounded-xl bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-kwamki-forest">Data penandatangan surat</h2>
        <p class="mt-1 text-sm text-gray-600">Isi nama, jabatan, NIP, lalu unggah gambar tanda tangan Kepala Kelurahan.</p>

        <form method="POST" action="{{ route('admin.permohonan.pengaturan-ttd.update') }}" enctype="multipart/form-data" class="mt-6 space-y-4" id="form-ttd">
            @csrf

            <div>
                <label for="lurah_jabatan" class="block text-sm font-medium text-gray-700">Jabatan</label>
                <input type="text" name="lurah_jabatan" id="lurah_jabatan" required maxlength="120"
                       value="{{ old('lurah_jabatan', $jabatan) }}"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('lurah_jabatan')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="lurah_nama" class="block text-sm font-medium text-gray-700">Nama lengkap</label>
                <input type="text" name="lurah_nama" id="lurah_nama" required maxlength="120"
                       value="{{ old('lurah_nama', $nama) }}"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('lurah_nama')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="lurah_nip" class="block text-sm font-medium text-gray-700">NIP</label>
                <input type="text" name="lurah_nip" id="lurah_nip" maxlength="50"
                       value="{{ old('lurah_nip', $nip) }}"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                @error('lurah_nip')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="lurah_ttd_gambar" class="block text-sm font-medium text-gray-700">Upload gambar tanda tangan (PNG/JPG, maks. 2 MB)</label>
                @if($gambarUrl)
                    <div class="mt-2 rounded-lg border border-gray-200 bg-gray-50 p-4 text-right">
                        <p class="mb-2 text-left text-xs text-gray-500">Gambar saat ini:</p>
                        <img src="{{ $gambarUrl }}" alt="Pratinjau TTD" id="ttd-current-img" class="ml-auto max-h-24 object-contain">
                        <label class="mt-3 flex items-center justify-end gap-2 text-sm text-gray-600">
                            <input type="checkbox" name="hapus_gambar" value="1" class="rounded border-gray-300">
                            Hapus gambar
                        </label>
                    </div>
                @endif
                <input type="file" name="lurah_ttd_gambar" id="lurah_ttd_gambar" accept="image/png,image/jpeg,image/webp"
                       class="mt-2 w-full text-sm text-gray-600 file:mr-3 file:rounded-lg file:border-0 file:bg-kwamki-sand file:px-3 file:py-2 file:text-sm file:font-semibold file:text-kwamki-forest">
                @error('lurah_ttd_gambar')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="rounded-lg bg-kwamki-forest px-4 py-2 text-sm font-semibold text-white hover:bg-kwamki-forest-dark">
                    Simpan Pengaturan
                </button>
                <a href="{{ route('admin.permohonan.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Kembali ke Antrian
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
