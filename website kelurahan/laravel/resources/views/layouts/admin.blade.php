<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ config('app.name', 'Kelurahan Kwamki') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-kwamki-sand/30 font-sans text-gray-900"
      data-permohonan-notif-url="{{ route('admin.permohonan.notifikasi') }}"
      data-permohonan-notif-role="{{ auth()->user()?->hasRole(\App\Models\User::ROLE_LURAH) ? 'lurah' : 'admin' }}">
    <div id="permohonan-toast-container" class="pointer-events-none fixed bottom-4 right-4 z-[60] flex max-w-sm flex-col gap-2" aria-live="polite"></div>
    @include('components.admin.sidebar')

    <div class="lg:pl-64">
        <x-admin.header />

        @if(session('success'))
            <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
            </div>
        @endif

        @if(($showAntrianLurahNotif ?? false) && ($menungguVerifikasiLurahCount ?? 0) > 0)
            <div id="antrian-lurah-banner" class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
                <div class="rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm text-indigo-900">
                    <span id="antrian-lurah-banner-text">{{ $menungguVerifikasiLurahCount }} surat menunggu verifikasi di antrian Anda.</span>
                    <a href="{{ route('admin.permohonan.index', ['status' => 'menunggu_verifikasi_kepala_kelurahan']) }}" class="ml-2 font-semibold underline">Lihat antrian</a>
                </div>
            </div>
        @endif

        @if(($showSuratDitandatanganiNotif ?? false) && ($suratDitandatanganiCount ?? 0) > 0)
            <div class="mx-auto max-w-7xl px-4 pt-4 sm:px-6 lg:px-8">
                <div class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                    {{ $suratDitandatanganiCount }} surat sudah ditandatangani Kepala Kelurahan dan menunggu pengiriman ke warga.
                    <a href="{{ route('admin.permohonan.index', ['status' => 'ditandatangani_kepala_kelurahan']) }}" class="ml-2 font-semibold underline">Lihat permohonan</a>
                </div>
            </div>
        @endif

        <main class="px-4 py-6 sm:px-6 lg:px-8">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
</body>
</html>
