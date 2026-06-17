@props(['permohonan', 'redirect' => 'index', 'showDetail' => true])

@php
    use App\Models\PermohonanLayanan;

    $user = auth()->user();
    $nextLabel = $permohonan->labelForNextActionFor($user);
    $hideGenericAdvance = $user?->isAdmin()
        && $permohonan->canKirimKeKepalaKelurahan($user)
        && $nextLabel === 'Kirim ke kepala kelurahan';
    $hideDiajukanAdvance = $user?->isAdmin()
        && PermohonanLayanan::normalizeStatus($permohonan->status) === PermohonanLayanan::STATUS_DIAJUKAN;
    $hideRevisiAdvance = $user?->isAdmin()
        && PermohonanLayanan::normalizeStatus($permohonan->status) === PermohonanLayanan::STATUS_REVISI_DARI_KEPALA_KELURAHAN;
@endphp

<div class="flex flex-wrap items-center justify-end gap-2">
    @if($permohonan->canAdvance($user) && $nextLabel && ! $hideGenericAdvance && ! $hideDiajukanAdvance && ! $hideRevisiAdvance)
        <form method="POST" action="{{ route('admin.permohonan.lanjutkan', $permohonan) }}" class="inline"
              onsubmit="return confirm('{{ $nextLabel }} permohonan {{ $permohonan->nomor }}?')">
            @csrf
            @method('PATCH')
            @if($redirect === 'show')
                <input type="hidden" name="redirect" value="show">
            @endif
            <button type="submit" class="rounded-lg bg-kwamki-gold px-3 py-1.5 text-xs font-semibold text-kwamki-forest-dark hover:bg-kwamki-gold-light">
                {{ $nextLabel }}
            </button>
        </form>
    @endif
    @if($showDetail)
        <a href="{{ route('admin.permohonan.show', $permohonan) }}" class="text-sm font-semibold text-kwamki-ocean hover:underline">Detail</a>
    @endif
</div>
