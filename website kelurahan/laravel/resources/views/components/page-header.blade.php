@props(['title', 'subtitle' => null])

<section class="relative overflow-hidden bg-gradient-to-br from-kwamki-forest-dark via-kwamki-forest to-kwamki-ocean text-white">
    <div class="absolute inset-0 opacity-20 papua-pattern"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
        <h1 class="hero-fade-in hero-delay-1 text-3xl font-bold md:text-4xl">{{ $title }}</h1>
        @if($subtitle)
            <p class="hero-fade-in hero-delay-2 mt-3 max-w-2xl text-lg text-white/80">{{ $subtitle }}</p>
        @endif
    </div>
</section>
