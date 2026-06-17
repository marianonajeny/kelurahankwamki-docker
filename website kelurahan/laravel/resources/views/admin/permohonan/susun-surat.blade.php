@extends('layouts.admin')

@php
    $pageTitle = 'Susun Surat';
    $breadcrumb = [
        'Permohonan' => route('admin.permohonan.index'),
        $permohonan->nomor => route('admin.permohonan.show', $permohonan),
        'Susun Surat' => null,
    ];
    $statusPermohonan = \App\Models\PermohonanLayanan::normalizeStatus($permohonan->status);
    $statusRevisiDariLurah = $statusPermohonan === \App\Models\PermohonanLayanan::STATUS_REVISI_DARI_KEPALA_KELURAHAN;
    $nomorSurat = old('nomor_surat', $permohonan->nomor_surat);
    $tanggalSurat = old('tanggal_surat', $permohonan->tanggal_surat?->format('Y-m-d') ?? now()->format('Y-m-d'));
    $previewUrl = $permohonan->hasSuratDraft() || (filled($nomorSurat) && filled($tanggalSurat))
        ? route('admin.permohonan.surat.preview', $permohonan).'?'.http_build_query(array_filter([
            'nomor_surat' => $nomorSurat,
            'tanggal_surat' => $tanggalSurat,
            'v' => $permohonan->updated_at?->timestamp ?? time(),
        ]))
        : null;
    $previewTtdUrl = route('admin.permohonan.surat.preview', $permohonan).'?'.http_build_query(array_filter([
        'nomor_surat' => $nomorSurat,
        'tanggal_surat' => $tanggalSurat,
        'with_ttd' => '1',
        'v' => $permohonan->updated_at?->timestamp ?? time(),
    ]));
@endphp

@section('title', 'Susun Surat — '.$permohonan->nomor)

@section('content')
<div class="mx-auto max-w-5xl space-y-6">
    @if($statusRevisiDariLurah)
        <div class="rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-900">
            <p class="font-semibold">Permintaan revisi dari Kepala Kelurahan</p>
            @if($permohonan->catatan_admin)
                <p class="mt-2 whitespace-pre-line rounded-lg border border-rose-100 bg-white px-4 py-3 text-rose-950">{{ $permohonan->catatan_admin }}</p>
            @else
                <p class="mt-2 text-rose-800">Kepala Kelurahan meminta perbaikan surat. Sesuaikan isi surat lalu terbitkan ulang.</p>
            @endif
        </div>
    @endif

    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <p class="text-xs uppercase text-gray-500">Permohonan</p>
            <p class="font-mono text-lg font-bold text-kwamki-forest">{{ $permohonan->nomor }}</p>
            <p class="mt-1 text-sm text-gray-600">{{ $permohonan->layanan->nama }} — {{ $permohonan->nama }}</p>
        </div>
        <a href="{{ route('admin.permohonan.show', $permohonan) }}"
           class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
            ← Kembali ke Detail
        </a>
    </div>

    @error('surat')
        <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ $message }}</div>
    @enderror

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-kwamki-forest">Nomor &amp; tanggal surat</h2>
            <p class="mt-1 text-sm text-gray-600">Isi nomor dan tanggal surat resmi, lalu simpan draft untuk melihat pratinjau.</p>

            <form method="POST" action="{{ route('admin.permohonan.susun-surat.store', $permohonan) }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="nomor_surat" class="block text-sm font-medium text-gray-700">Nomor surat</label>
                    <input type="text" name="nomor_surat" id="nomor_surat" required maxlength="100"
                           value="{{ $nomorSurat }}"
                           placeholder="Contoh: 470 / 001 / KWAMKI / VI / 2026"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('nomor_surat')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="tanggal_surat" class="block text-sm font-medium text-gray-700">Tanggal surat</label>
                    <input type="date" name="tanggal_surat" id="tanggal_surat" required
                           value="{{ $tanggalSurat }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('tanggal_surat')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <button type="submit"
                        class="w-full rounded-lg bg-kwamki-gold px-4 py-2 text-sm font-semibold text-kwamki-forest-dark hover:bg-kwamki-gold-light">
                    Simpan Draft
                </button>
            </form>

            <div class="mt-8 space-y-3 border-t border-gray-100 pt-6">
                <h3 class="text-sm font-semibold text-kwamki-forest">Terbitkan &amp; kirim</h3>

                <form method="POST" action="{{ route('admin.permohonan.terbitkan-surat', $permohonan) }}"
                      onsubmit="return confirm('Terbitkan surat PDF untuk permohonan {{ $permohonan->nomor }}?')">
                    @csrf
                    <input type="hidden" name="redirect" value="susun-surat">
                    <input type="hidden" name="nomor_surat" value="{{ $nomorSurat }}">
                    <input type="hidden" name="tanggal_surat" value="{{ $tanggalSurat }}">
                    <button type="submit"
                            class="w-full rounded-lg bg-kwamki-gold px-4 py-2 text-sm font-semibold text-kwamki-forest-dark hover:bg-kwamki-gold-light"
                            @disabled(! $permohonan->hasSuratDraft() && (! filled($nomorSurat) || ! filled($tanggalSurat)))>
                        Terbitkan Surat PDF
                    </button>
                </form>

                @if($permohonan->hasSuratTerbit())
                    <form method="POST" action="{{ route('admin.permohonan.kirim-ke-kepala-kelurahan', $permohonan) }}"
                          onsubmit="return confirm('Kirim permohonan {{ $permohonan->nomor }} ke Kepala Kelurahan untuk verifikasi?')">
                        @csrf
                        <button type="submit"
                                class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                                @disabled(! $permohonan->canKirimKeKepalaKelurahan(auth()->user()))>
                            Kirim ke Kepala Kelurahan
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.permohonan.kirim-ke-kepala-kelurahan-ttd', $permohonan) }}"
                          onsubmit="return confirm('Terbitkan surat dan kirim langsung ke Kepala Kelurahan?')">
                        @csrf
                        <input type="hidden" name="nomor_surat" value="{{ $nomorSurat }}">
                        <input type="hidden" name="tanggal_surat" value="{{ $tanggalSurat }}">
                        <button type="submit"
                                class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700"
                                @disabled(! filled($nomorSurat) || ! filled($tanggalSurat))>
                            Terbitkan &amp; Kirim ke Kepala Kelurahan
                        </button>
                    </form>
                @endif

                @if($permohonan->hasSuratTerbit())
                    <a href="{{ route('admin.permohonan.surat.unduh', $permohonan) }}?v={{ $permohonan->updated_at?->timestamp ?? time() }}"
                       class="flex w-full items-center justify-center rounded-lg border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Unduh PDF
                    </a>
                @endif

                @if($previewUrl)
                    <a href="{{ $previewTtdUrl }}" target="_blank" rel="noopener"
                       class="flex w-full items-center justify-center rounded-lg border border-kwamki-gold px-4 py-2 text-sm font-semibold text-kwamki-forest-dark hover:bg-kwamki-gold/10">
                        Pratinjau dengan TTD
                    </a>
                @endif
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-kwamki-forest">Pratinjau surat</h2>
            <p class="mt-1 text-sm text-gray-600">Tampilan surat sesuai jenis layanan. Simpan draft untuk memperbarui pratinjau.</p>

            @if($previewUrl)
                <iframe src="{{ $previewUrl }}" title="Pratinjau surat {{ $permohonan->nomor }}"
                        class="mt-4 h-[min(75vh,720px)] w-full rounded-lg border border-gray-200 bg-gray-50"></iframe>
            @else
                <p class="mt-4 rounded-lg border border-amber-200 bg-amber-50 px-4 py-8 text-center text-sm text-amber-900">
                    Isi nomor dan tanggal surat, lalu klik <strong>Simpan Draft</strong> untuk melihat pratinjau.
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
