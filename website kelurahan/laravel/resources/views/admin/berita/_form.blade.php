@props(['berita' => null])

<div class="space-y-4">
    <div>
        <label for="judul" class="block text-sm font-medium text-gray-700">Judul *</label>
        <input type="text" name="judul" id="judul" value="{{ old('judul', $berita?->judul) }}" required
               class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-kwamki-forest focus:ring-kwamki-forest @error('judul') border-red-500 @enderror">
        @error('judul')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="slug" class="block text-sm font-medium text-gray-700">Slug (opsional)</label>
        <input type="text" name="slug" id="slug" value="{{ old('slug', $berita?->slug) }}"
               class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-kwamki-forest focus:ring-kwamki-forest @error('slug') border-red-500 @enderror">
        @error('slug')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="ringkasan" class="block text-sm font-medium text-gray-700">Ringkasan</label>
        <textarea name="ringkasan" id="ringkasan" rows="2"
                  class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">{{ old('ringkasan', $berita?->ringkasan) }}</textarea>
    </div>
    <div>
        <label for="isi" class="block text-sm font-medium text-gray-700">Isi Berita *</label>
        <textarea name="isi" id="isi" rows="8" required
                  class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 @error('isi') border-red-500 @enderror">{{ old('isi', $berita?->isi) }}</textarea>
        @error('isi')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
    </div>
    <div>
        <label for="gambar" class="block text-sm font-medium text-gray-700">
            Gambar {{ $berita ? '(kosongkan jika tidak ganti)' : '(opsional)' }}
        </label>
        <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/png,image/gif,image/webp,.jpg,.jpeg,.png,.gif,.webp"
               class="mt-1 w-full text-sm @error('gambar') border-red-500 @enderror">
        <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG, WEBP. Maksimal 10 MB. Foto iPhone (HEIC) perlu dikonversi ke JPG dulu.</p>
        @error('gambar')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
        @if($berita?->gambar)
        <img src="{{ asset('storage/'.$berita->gambar) }}" alt="" class="mt-2 h-24 rounded object-cover">
        @endif
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="published_at" class="block text-sm font-medium text-gray-700">Tanggal Publikasi</label>
            <input type="datetime-local" name="published_at" id="published_at"
                   value="{{ old('published_at', $berita?->published_at?->format('Y-m-d\TH:i')) }}"
                   class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
        </div>
        <div class="flex items-end pb-2">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="is_published" value="1"
                       {{ old('is_published', $berita?->is_published ?? true) ? 'checked' : '' }}
                       class="rounded border-gray-300 text-kwamki-forest">
                <span class="text-sm text-gray-700">Publikasikan</span>
            </label>
        </div>
    </div>
</div>
