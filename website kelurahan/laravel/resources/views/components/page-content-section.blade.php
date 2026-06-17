@props([
    'variant' => 'warm',
    'maxWidth' => 'max-w-7xl',
    'padding' => 'py-16',
])

@php
$classes = match($variant) {
    'warm' => 'section-bg-warm section-pattern relative overflow-hidden',
    'alt'  => 'section-bg-alt section-pattern relative overflow-hidden',
    'news' => 'relative border-t border-kwamki-green/25 bg-gradient-to-b from-white to-kwamki-green-light/40',
    default => 'section-bg-warm section-pattern relative overflow-hidden',
};
$innerZ = in_array($variant, ['warm', 'alt']) ? 'relative z-10' : '';
@endphp

<section {{ $attributes->merge(['class' => "$classes $padding"]) }}>
    <div class="{{ $innerZ }} mx-auto {{ $maxWidth }} px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </div>
</section>
