@props(['layanan' => null])

@php
    $katOptions = [
        'administrasi' => 'Administrasi Kependudukan',
        'sosial' => 'Sosial & Bantuan',
        'pengaduan' => 'Pengaduan & Partisipasi',
        'lainnya' => 'Lainnya',
    ];
    $ikonOptions = [
        'document' => 'Dokumen / surat',
        'user' => 'Identitas / penduduk',
        'id' => 'KTP / identitas',
        'home' => 'Domisili / rumah',
        'heart' => 'Sosial / bantuan',
        'business' => 'Usaha / UMKM',
        'baby' => 'Kelahiran / keluarga',
        'complaint' => 'Pengaduan / chat',
    ];
@endphp

<div class="space-y-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="sm:col-span-2">
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama layanan *</label>
            <input type="text" name="nama" id="nama" value="{{ old('nama', $layanan?->nama) }}" required class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 @error('nama') border-red-500 @enderror">
            @error('nama')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="slug" class="block text-sm font-medium text-gray-700">Slug URL (kosongkan = otomatis)</label>
            <input type="text" name="slug" id="slug" value="{{ old('slug', $layanan?->slug) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 font-mono text-sm @error('slug') border-red-500 @enderror">
            @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
            <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori *</label>
            <select name="kategori" id="kategori" required class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
                @foreach($katOptions as $val => $label)
                    <option value="{{ $val }}" @selected(old('kategori', $layanan?->kategori ?? 'administrasi') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="ikon" class="block text-sm font-medium text-gray-700">Ikon kartu</label>
            <select name="ikon" id="ikon" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
                @foreach($ikonOptions as $val => $label)
                    <option value="{{ $val }}" @selected(old('ikon', $layanan?->ikon ?? 'document') === $val)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="urutan" class="block text-sm font-medium text-gray-700">Urutan</label>
            <input type="number" name="urutan" id="urutan" value="{{ old('urutan', $layanan?->urutan ?? 0) }}" min="0" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
        </div>
    </div>

    <div>
        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Ringkasan / deskripsi singkat</label>
        <textarea name="deskripsi" id="deskripsi" rows="3" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">{{ old('deskripsi', $layanan?->deskripsi) }}</textarea>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="estimasi_waktu" class="block text-sm font-medium text-gray-700">Jangka waktu *</label>
            <input type="text" name="estimasi_waktu" id="estimasi_waktu" value="{{ old('estimasi_waktu', $layanan?->estimasi_waktu ?? '3 hari kerja') }}" required placeholder="Mis. 1 hari kerja" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
        </div>
        <div>
            <label for="biaya" class="block text-sm font-medium text-gray-700">Biaya / tarif *</label>
            <input type="text" name="biaya" id="biaya" value="{{ old('biaya', $layanan?->biaya ?? 'Gratis') }}" required placeholder="Gratis" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
        </div>
    </div>

    <div>
        <label for="persyaratan" class="block text-sm font-medium text-gray-700">Persyaratan (satu baris per butir)</label>
        <textarea name="persyaratan" id="persyaratan" rows="6" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 font-mono text-xs" placeholder="- Fotokopi KTP">{{ old('persyaratan', $layanan?->persyaratan) }}</textarea>
    </div>

    <div>
        <label for="alur" class="block text-sm font-medium text-gray-700">Alur / prosedur (satu baris per langkah)</label>
        <textarea name="alur" id="alur" rows="6" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 font-mono text-xs" placeholder="1. Datang ke loket">{{ old('alur', $layanan?->alur) }}</textarea>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="petugas" class="block text-sm font-medium text-gray-700">Petugas / unit</label>
            <input type="text" name="petugas" id="petugas" value="{{ old('petugas', $layanan?->petugas) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
        </div>
        <div>
            <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi / loket</label>
            <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $layanan?->lokasi) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="dokumen_url" class="block text-sm font-medium text-gray-700">Link formulir PDF (opsional)</label>
            <input type="url" name="dokumen_url" id="dokumen_url" value="{{ old('dokumen_url', $layanan?->dokumen_url) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2" placeholder="https://">
        </div>
        <div>
            <label for="link_url" class="block text-sm font-medium text-gray-700">Link terkait (opsional)</label>
            <input type="url" name="link_url" id="link_url" value="{{ old('link_url', $layanan?->link_url) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
        </div>
    </div>

    <div class="flex flex-wrap gap-6">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $layanan?->is_active ?? true) ? 'checked' : '' }} class="rounded border-gray-300">
            <span class="text-sm text-gray-700">Tampil di website (aktif)</span>
        </label>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="menerima_permohonan_online" value="1" {{ old('menerima_permohonan_online', $layanan?->menerima_permohonan_online ?? true) ? 'checked' : '' }} class="rounded border-gray-300">
            <span class="text-sm text-gray-700">Terima permohonan online</span>
        </label>
    </div>
</div>
