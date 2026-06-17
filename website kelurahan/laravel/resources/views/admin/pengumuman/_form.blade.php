@props(['pengumuman' => null])

<div class="space-y-4">
    <div>
        <label for="judul" class="block text-sm font-medium text-gray-700">Judul *</label>
        <input type="text" name="judul" id="judul" value="{{ old('judul', $pengumuman?->judul) }}" required class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
    </div>
    <div>
        <label for="isi" class="block text-sm font-medium text-gray-700">Isi Pengumuman *</label>
        <textarea name="isi" id="isi" rows="8" required class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">{{ old('isi', $pengumuman?->isi) }}</textarea>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai', $pengumuman?->tanggal_mulai?->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
        </div>
        <div>
            <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ old('tanggal_akhir', $pengumuman?->tanggal_akhir?->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2">
        </div>
    </div>
    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $pengumuman?->is_published ?? true) ? 'checked' : '' }} class="rounded border-gray-300">
        <span class="text-sm text-gray-700">Publikasikan</span>
    </label>
</div>
