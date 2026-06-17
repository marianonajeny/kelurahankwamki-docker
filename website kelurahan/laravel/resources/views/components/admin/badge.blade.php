@props(['label', 'tone' => 'neutral'])

@php
    $toneClass = match ($tone) {
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'info' => 'badge-info',
        'danger' => 'badge-danger',
        default => 'badge-neutral',
    };
@endphp

<span {{ $attributes->merge(['class' => $toneClass]) }}>{{ $label }}</span>
