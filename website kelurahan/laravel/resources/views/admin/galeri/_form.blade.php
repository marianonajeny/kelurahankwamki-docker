@props(['galeri' => null])

<div class="space-y-4">
    <div>
        <label for="judul" class="block text-sm font-medium text-gray-700">Judul *</label>
        <input type="text" name="judul" id="judul" value="{{ old('judul', $galeri?->judul) }}" required class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
    </div>
    <div>
        <label for="gambar" class="block text-sm font-medium text-gray-700">Gambar {{ $galeri ? '(kosongkan jika tidak ganti)' : '*' }}</label>
        <input type="file" name="gambar" id="gambar" accept="image/*" {{ $galeri ? '' : 'required' }} class="mt-1 w-full text-sm">
        @if($galeri?->gambar)
        <img src="{{ asset('storage/'.$galeri->gambar) }}" alt="" class="mt-2 h-32 rounded object-cover">
        @endif
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="kategori" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="kategori" id="kategori" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
                @foreach(['kegiatan', 'kesehatan', 'pemerintahan', 'pelayanan'] as $kat)
                <option value="{{ $kat }}" {{ old('kategori', $galeri?->kategori) === $kat ? 'selected' : '' }}>{{ ucfirst($kat) }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="urutan" class="block text-sm font-medium text-gray-700">Urutan</label>
            <input type="number" name="urutan" id="urutan" value="{{ old('urutan', $galeri?->urutan ?? 0) }}" min="0" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
        </div>
    </div>
    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $galeri?->is_published ?? true) ? 'checked' : '' }} class="rounded border-gray-300">
        <span class="text-sm text-gray-700">Publikasikan</span>
    </label>
</div>
