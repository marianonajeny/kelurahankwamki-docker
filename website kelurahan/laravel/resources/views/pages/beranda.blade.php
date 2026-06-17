@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    {{-- Hero: foto full halaman dengan overlay gradient, teks di atas --}}
    <section class="relative min-h-[calc(100dvh-4rem)] flex items-center overflow-hidden bg-kwamki-forest-dark text-white" aria-label="Kantor Kelurahan Kwamki, Timika">
        <img src="{{ $siteHeroImage }}"
             alt="Gerbang Kantor Kelurahan Kwamki"
             class="absolute inset-0 h-full w-full object-cover object-center"
             fetchpriority="high">
        <div class="absolute inset-0 bg-gradient-to-r from-kwamki-forest-dark/95 via-kwamki-forest/60 to-transparent"></div>
        <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <span class="hero-fade-in hero-delay-1 inline-block rounded-full border border-kwamki-gold/30 bg-kwamki-gold/20 px-4 py-1 text-sm font-medium text-kwamki-gold-light">
                Mimika Baru — Papua Tengah
            </span>
            <h1 class="hero-fade-in hero-delay-2 mt-6 max-w-3xl text-4xl font-bold leading-tight md:text-5xl lg:text-6xl">
                Selamat Datang di<br>
                <span class="text-kwamki-gold">Kelurahan Kwamki</span>
            </h1>
            <p class="hero-fade-in hero-delay-3 mt-6 max-w-2xl text-lg text-white/85">
                {{ $profilSingkat?->konten ? strip_tags(\Illuminate\Support\Str::limit($profilSingkat->konten, 200)) : 'Melayani masyarakat dengan transparansi, akuntabilitas, dan dedikasi untuk pembangunan wilayah Mimika Baru.' }}
            </p>
            <div class="hero-fade-in hero-delay-4 mt-8 flex flex-wrap gap-4">
                <a href="{{ route('layanan') }}" class="btn-primary bg-kwamki-gold text-kwamki-forest-dark hover:bg-kwamki-gold-light">Layanan Publik</a>
                <a href="{{ route('profil') }}" class="inline-flex items-center justify-center rounded-lg border border-white/40 bg-white/10 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-kwamki-green/30">Profil Kelurahan</a>
            </div>
        </div>
    </section>

    {{-- Quick links layanan --}}
    @if($layananUtama->isNotEmpty())
    <section class="section-bg-warm section-pattern relative overflow-hidden py-16">
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <h2 class="reveal section-title section-title-accent section-title-accent-center text-center">Layanan Publik</h2>
            <p class="reveal mt-2 text-center text-kwamki-teal/80">Akses layanan administrasi kelurahan secara mudah</p>
            <div class="reveal-stagger mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($layananUtama as $layanan)
                <a href="{{ $layanan->publicUrl() }}" class="reveal card card-interactive card-accent group flex items-start gap-4 p-5">
                    <div class="icon-accent">
                        <x-layanan-icon :ikon="$layanan->ikon" class="h-6 w-6" />
                    </div>
                    <div>
                        <h3 class="font-semibold text-kwamki-green">{{ $layanan->nama }}</h3>
                        <p class="mt-1 text-sm text-gray-600 line-clamp-2">{{ $layanan->deskripsi }}</p>
                    </div>
                </a>
                @endforeach
            </div>
            <div class="reveal mt-8 text-center">
                <a href="{{ route('layanan') }}" class="btn-secondary border-kwamki-green text-kwamki-green hover:bg-kwamki-green-light">Lihat Semua Layanan</a>
            </div>
        </div>
    </section>
    @endif

    {{-- Berita --}}
    @if($beritaTerbaru->isNotEmpty())
    <section class="relative border-t border-kwamki-green/25 bg-gradient-to-b from-white to-kwamki-green-light/40 py-16">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="reveal flex items-end justify-between">
                <div>
                    <h2 class="section-title section-title-accent">Berita Terbaru</h2>
                    <p class="mt-2 text-gray-600">Informasi kegiatan dan perkembangan kelurahan</p>
                </div>
                <a href="{{ route('berita.index') }}" class="link-teal hidden text-sm sm:inline">Semua berita &rarr;</a>
            </div>
            <div class="reveal-stagger mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach($beritaTerbaru as $berita)
                    <x-card-berita :berita="$berita" />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Pengumuman + Galeri --}}
    <section class="section-bg-alt section-pattern relative overflow-hidden py-16">
        <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-10 lg:grid-cols-2">
                <div>
                    <h2 class="reveal section-title section-title-accent">Pengumuman</h2>
                    <ul class="reveal-stagger mt-6 space-y-4">
                        @forelse($pengumumanTerbaru as $p)
                        <li class="reveal card announcement-card p-4">
                            <span class="announcement-date">{{ $p->created_at->translatedFormat('d M Y') }}</span>
                            <h3 class="mt-1 font-semibold text-kwamki-green">{{ $p->judul }}</h3>
                            <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ strip_tags($p->isi) }}</p>
                        </li>
                        @empty
                        <li class="text-gray-500 text-sm">Belum ada pengumuman.</li>
                        @endforelse
                    </ul>
                    <a href="{{ route('pengumuman') }}" class="link-teal reveal mt-4 inline-block text-sm">Semua pengumuman &rarr;</a>
                </div>
                <div>
                    <h2 class="reveal section-title section-title-accent">Galeri Kegiatan</h2>
                    <div class="reveal-stagger mt-6 grid grid-cols-3 gap-2">
                        @foreach($galeriHighlight as $g)
                        <a href="{{ route('galeri') }}" data-lightbox="{{ $g->gambar ? asset('storage/'.$g->gambar) : $sitePlaceholderImage }}" class="reveal block aspect-square overflow-hidden rounded-lg ring-2 ring-transparent transition hover:ring-kwamki-teal/40">
                            <img src="{{ $g->gambar ? asset('storage/'.$g->gambar) : $sitePlaceholderImage }}" alt="{{ $g->judul }}" class="h-full w-full object-cover transition duration-300 hover:scale-110" loading="lazy">
                        </a>
                        @endforeach
                    </div>
                    <a href="{{ route('galeri') }}" class="link-teal reveal mt-4 inline-block text-sm">Lihat galeri lengkap &rarr;</a>
                </div>
            </div>
        </div>
    </section>
@endsection
