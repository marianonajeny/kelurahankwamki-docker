@props(['berita'])

<a href="{{ route('berita.show', $berita) }}" class="card card-interactive reveal group overflow-hidden">
    @if($berita->gambar)
        <img src="{{ asset('storage/'.$berita->gambar) }}" alt="{{ $berita->judul }}" class="aspect-video w-full object-cover transition group-hover:scale-105" loading="lazy">
    @else
        <div class="aspect-video w-full bg-kwamki-sand flex items-center justify-center text-sm text-gray-500">Berita</div>
    @endif
    <div class="p-4">
        <span class="text-xs font-medium text-kwamki-gold">{{ $berita->published_at?->translatedFormat('d M Y') }}</span>
        <h3 class="mt-1 font-semibold text-kwamki-forest line-clamp-2 group-hover:text-kwamki-ocean">{{ $berita->judul }}</h3>
        <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ $berita->ringkasan }}</p>
    </div>
</a>
