<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Beranda') — {{ config('app.name', 'Kelurahan Kwamki') }}</title>
    <meta name="description" content="@yield('meta_description', 'Website resmi Kelurahan Kwamki, Distrik Mimika Baru, Kabupaten Mimika, Papua Tengah.')">

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen flex flex-col papua-pattern">
    @include('components.navbar')

    @if(isset($breadcrumb))
        <div class="reveal is-visible border-b border-gray-100 bg-white/80 backdrop-blur">
            <div class="mx-auto max-w-7xl px-4 py-3 sm:px-6 lg:px-8">
                <nav class="text-sm text-gray-500" aria-label="Breadcrumb">
                    <a href="{{ route('home') }}" class="hover:text-kwamki-forest">Beranda</a>
                    @foreach($breadcrumb as $label => $url)
                        <span class="mx-2">/</span>
                        @if($url)
                            <a href="{{ $url }}" class="hover:text-kwamki-forest">{{ $label }}</a>
                        @else
                            <span class="text-kwamki-forest font-medium">{{ $label }}</span>
                        @endif
                    @endforeach
                </nav>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-50 border-b border-green-200">
            <div class="mx-auto max-w-7xl px-4 py-3 text-sm text-green-800 sm:px-6">
                {{ session('success') }}
            </div>
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    @include('components.footer')

    @stack('scripts')
</body>
</html>
