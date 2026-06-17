@extends('layouts.app')

@section('title', 'Cek status permohonan')
@section('meta_description', 'Lacak status permohonan layanan Kelurahan Kwamki dengan nomor dan verifikasi nomor HP.')

@php $breadcrumb = ['Layanan' => route('layanan'), 'Cek status' => null]; @endphp

@section('content')
    <x-page-header title="Cek status permohonan" subtitle="Masukkan nomor permohonan dan nomor HP lengkap Anda." />

    <section class="border-b border-gray-100 bg-kwamki-cream/40 py-3">
        <div class="mx-auto max-w-7xl px-4 text-sm text-gray-700 sm:px-6 lg:px-8">
            <strong>Jam layanan:</strong> {{ $jamLayanan }}
        </div>
    </section>

    <x-page-content-section variant="alt" maxWidth="max-w-lg" padding="py-12">
        <div class="reveal is-visible card card-accent p-8">
            <form method="POST" action="{{ route('layanan.cek-status.lookup') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="nomor" class="block text-sm font-medium text-gray-700">Nomor permohonan</label>
                    <input type="text" name="nomor" id="nomor" value="{{ old('nomor', $nomor_input ?? '') }}" placeholder="KW-20260516-0001" required class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 font-mono text-sm focus:border-kwamki-forest">
                    @error('nomor')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="no_hp" class="block text-sm font-medium text-gray-700">Nomor HP</label>
                    <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}" maxlength="20" inputmode="tel" placeholder="081234567890" required class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2 text-sm">
                    @error('no_hp')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                @if(isset($error))
                    <p class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800">{{ $error }}</p>
                @endif
                <button type="submit" class="btn-primary w-full">Periksa</button>
            </form>
        </div>

        @if(isset($permohonan) && $permohonan)
            @if(isset($whatsappSent) && $whatsappSent)
                <p class="mt-8 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800">Ringkasan status telah dikirim ke WhatsApp Anda.</p>
            @elseif(isset($whatsappSent) && ! $whatsappSent)
                <p class="mt-8 rounded-lg bg-amber-50 px-4 py-3 text-sm text-amber-800">Status ditampilkan di bawah. Pengiriman WhatsApp gagal, silakan hubungi kelurahan jika diperlukan.</p>
            @endif
            <div class="reveal is-visible card card-accent mt-8 p-8">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-xs uppercase text-gray-500">Nomor</p>
                        <p class="font-mono text-lg font-bold text-kwamki-forest">{{ $permohonan->nomor }}</p>
                    </div>
                    <x-status-badge :status="$permohonan->status" />
                </div>
                <dl class="mt-6 grid gap-3 text-sm">
                    <div>
                        <dt class="text-gray-500">Nama pemohon</dt>
                        <dd class="font-medium">{{ $permohonan->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">NIK</dt>
                        <dd class="font-mono">{{ $permohonan->nik }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Status</dt>
                        <dd><x-status-badge :status="$permohonan->status" /></dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Layanan</dt>
                        <dd class="font-medium">{{ $permohonan->layanan->nama }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500">Diajukan</dt>
                        <dd>{{ $permohonan->created_at->translatedFormat('d F Y H:i') }}</dd>
                    </div>
                    @if($permohonan->diproses_at)
                        <div>
                            <dt class="text-gray-500">Mulai diproses</dt>
                            <dd>{{ $permohonan->diproses_at->translatedFormat('d F Y H:i') }}</dd>
                        </div>
                    @endif
                    @if($permohonan->selesai_at)
                        <div>
                            <dt class="text-gray-500">Selesai</dt>
                            <dd>{{ $permohonan->selesai_at->translatedFormat('d F Y H:i') }}</dd>
                        </div>
                    @endif
                    @if($permohonan->ditandatangani_at)
                        <div>
                            <dt class="text-gray-500">Ditandatangani Kepala Kelurahan</dt>
                            <dd>{{ $permohonan->ditandatangani_at->translatedFormat('d F Y H:i') }}</dd>
                        </div>
                    @endif
                    @if($permohonan->catatan_admin && in_array(\App\Models\PermohonanLayanan::normalizeStatus($permohonan->status), [\App\Models\PermohonanLayanan::STATUS_SELESAI, \App\Models\PermohonanLayanan::STATUS_DITOLAK, \App\Models\PermohonanLayanan::STATUS_REVISI_DARI_KEPALA_KELURAHAN], true))
                        <div>
                            <dt class="text-gray-500">Catatan</dt>
                            <dd class="whitespace-pre-line text-gray-800">{{ $permohonan->catatan_admin }}</dd>
                        </div>
                    @endif
                </dl>
                <p class="mt-6 text-xs text-gray-500">Jika status tidak berubah sesuai jangka waktu layanan, silakan menghubungi kantor kelurahan.</p>
            </div>
        @endif
    </x-page-content-section>
@endsection
