@props(['layanan'])

@php
    $katLabels = \App\Models\Layanan::kategoriLabels();
    $katLabel = $katLabels[$layanan->kategori] ?? ucfirst($layanan->kategori);
@endphp

<article class="card card-interactive card-accent reveal group flex flex-col p-6">
    <div class="flex items-start gap-4">
        <div class="icon-accent">
            <x-layanan-icon :ikon="$layanan->ikon" class="h-6 w-6" />
        </div>
        <div class="min-w-0 flex-1">
            <span class="inline-block rounded-full bg-kwamki-sand px-2.5 py-0.5 text-xs font-medium text-kwamki-forest">
                {{ $katLabel }}
            </span>
            <h3 class="mt-2 text-lg font-semibold text-kwamki-forest">{{ $layanan->nama }}</h3>
        </div>
    </div>

    @if($layanan->deskripsi)
        <p class="mt-4 text-sm text-gray-600 line-clamp-3">{{ $layanan->deskripsi }}</p>
    @endif

    @if($layanan->menerima_permohonan_online)
        <div class="mt-5 flex flex-col gap-2 sm:flex-row">
            <a href="{{ $layanan->publicUrl() }}" class="btn-secondary flex-1 text-center">
                Detail Persyaratan
            </a>
            <a href="{{ $layanan->publicAjukanUrl() }}" class="btn-primary flex-1 text-center">
                Ajukan Online
            </a>
        </div>
    @else
        <div class="mt-5">
            <a href="{{ $layanan->publicUrl() }}" class="btn-primary w-full text-center">
                Detail &amp; Persyaratan
            </a>
        </div>
    @endif
</article>
