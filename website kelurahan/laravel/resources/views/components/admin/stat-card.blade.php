@props(['label', 'value', 'color' => 'forest', 'href' => null])

@php
    $borderClass = match($color) {
        'ocean' => 'border-kwamki-ocean',
        'gold' => 'border-kwamki-gold',
        'red' => 'border-red-400',
        default => 'border-kwamki-forest',
    };
    $textClass = match($color) {
        'ocean' => 'text-kwamki-ocean',
        'gold' => 'text-kwamki-gold',
        'red' => 'text-red-600',
        default => 'text-kwamki-forest',
    };
@endphp

@if($href)
<a href="{{ $href }}" class="block rounded-xl border-l-4 bg-white p-6 shadow-sm transition hover:shadow-md {{ $borderClass }}">
@else
<div class="rounded-xl border-l-4 bg-white p-6 shadow-sm {{ $borderClass }}">
@endif
    <p class="text-sm text-gray-500">{{ $label }}</p>
    <p class="mt-1 text-3xl font-bold {{ $textClass }}">{{ $value }}</p>
    {{ $slot ?? '' }}
@if($href)
</a>
@else
</div>
@endif
