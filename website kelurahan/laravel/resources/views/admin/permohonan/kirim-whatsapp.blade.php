@extends('layouts.admin')

@php
    $pageTitle = 'Kirim Surat via WhatsApp';
    $breadcrumb = ['Kirim WhatsApp' => null];
@endphp

@section('title', 'Kirim WhatsApp')

@section('content')
<div class="mb-6">
    <p class="text-sm text-gray-600">Kirim PDF surat ke pemohon lewat WhatsApp. Daftar berikut surat yang sudah ditandatangani kepala kelurahan dan siap dikirim.</p>
</div>

<div class="overflow-hidden rounded-xl bg-white shadow-sm">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px] text-sm">
            <thead class="bg-kwamki-sand text-left text-gray-600">
                <tr>
                    <th class="px-4 py-3">Nomor</th>
                    <th class="px-4 py-3">Layanan</th>
                    <th class="px-4 py-3">Pemohon</th>
                    <th class="px-4 py-3">No. HP</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($permohonans as $p)
                <tr class="hover:bg-gray-50/80">
                    <td class="px-4 py-3 font-mono text-xs font-medium">{{ $p->nomor }}</td>
                    <td class="max-w-[180px] truncate px-4 py-3" title="{{ $p->layanan?->nama }}">{{ $p->layanan?->nama }}</td>
                    <td class="px-4 py-3">{{ $p->nama }}</td>
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $p->no_hp }}</td>
                    <td class="px-4 py-3"><x-status-badge :status="$p->status" /></td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-end gap-2">
                            @if($p->canKirimSuratKeWhatsappWarga())
                                <x-admin.whatsapp-kirim-button :permohonan="$p" size="sm" />
                            @endif
                            <a href="{{ route('admin.permohonan.show', $p) }}"
                               class="rounded-lg border border-gray-300 px-3 py-1.5 text-xs font-medium text-gray-700 hover:bg-gray-50">
                                Detail
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">Tidak ada surat siap dikirim.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($permohonans->hasPages())
        <div class="border-t border-gray-100 px-4 py-3">{{ $permohonans->links() }}</div>
    @endif
</div>
@endsection
