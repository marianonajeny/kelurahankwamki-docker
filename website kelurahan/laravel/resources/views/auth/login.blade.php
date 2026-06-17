@extends('layouts.guest')

@section('title', 'Masuk Dashboard Admin')

@section('content')
<div class="w-full max-w-md">
    <div class="mb-8 text-center text-white">
        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-kwamki-gold text-2xl font-bold text-kwamki-forest-dark shadow-lg">
            KW
        </div>
        <h1 class="text-2xl font-bold">Masuk Dashboard Admin</h1>
        <p class="mt-1 text-white/70 text-sm">Khusus petugas administrasi Kelurahan Kwamki</p>
    </div>

    <div class="rounded-2xl bg-white p-8 shadow-2xl">
        @if($errors->any())
        <ul class="mb-4 rounded-lg bg-red-50 p-3 text-sm text-red-700">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                       placeholder="admin"
                       autocomplete="username"
                       class="mt-1 w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:border-kwamki-forest focus:ring-kwamki-forest">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative mt-1">
                    <input id="password" type="password" name="password" required
                           autocomplete="current-password"
                           class="w-full rounded-lg border border-gray-300 py-2.5 pl-4 pr-11 focus:border-kwamki-forest focus:ring-kwamki-forest">
                    <button type="button" id="toggle-password"
                            class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700"
                            aria-label="Tampilkan password" aria-pressed="false">
                        <svg id="icon-password-show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg id="icon-password-hide" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
            </div>
            <label class="flex items-center gap-2 text-sm text-gray-600">
                <input type="checkbox" name="remember" class="rounded border-gray-300 text-kwamki-forest focus:ring-kwamki-forest">
                Ingat saya
            </label>
            <button type="submit" class="btn-primary w-full">Masuk</button>
        </form>

        <p class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-kwamki-ocean hover:text-kwamki-forest">&larr; Kembali ke website</a>
        </p>
    </div>
</div>

<script>
    (function () {
        const input = document.getElementById('password');
        const toggle = document.getElementById('toggle-password');
        const iconShow = document.getElementById('icon-password-show');
        const iconHide = document.getElementById('icon-password-hide');

        if (!input || !toggle || !iconShow || !iconHide) {
            return;
        }

        toggle.addEventListener('click', function () {
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            iconShow.classList.toggle('hidden', isHidden);
            iconHide.classList.toggle('hidden', !isHidden);
            toggle.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            toggle.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
        });
    })();
</script>
@endsection
