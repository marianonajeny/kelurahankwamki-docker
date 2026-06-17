@props(['status'])

@php
    use App\Models\PermohonanLayanan;

    $label = PermohonanLayanan::statusLabel($status);
    $toneClass = match (PermohonanLayanan::statusTone($status)) {
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'info' => 'badge-info',
        'danger' => 'badge-danger',
        default => 'badge-neutral',
    };
@endphp

<span class="{{ $toneClass }}">{{ $label }}</span>
