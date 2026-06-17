@extends('layouts.admin')
@php
    $pageTitle = 'Pengaturan Akun';
    $breadcrumb = ['Pengaturan Akun' => null];
    $roleLabel = $user->hasRole(\App\Models\User::ROLE_LURAH) ? 'Kepala Kelurahan' : 'Administrator';
@endphp
@section('title', 'Pengaturan Akun')

@section('content')
<div class="mx-auto max-w-2xl space-y-6">
    @if(session('success'))
        <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.pengaturan-akun.update') }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-kwamki-forest">Profil</h2>
            <p class="mt-1 text-sm text-gray-600">Perbarui nama dan email akun Anda.</p>

            <div class="mt-6 space-y-4">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama lengkap</label>
                    <input type="text" name="name" id="name" required maxlength="255"
                           value="{{ old('name', $user->name) }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('name')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required maxlength="255"
                           value="{{ old('email', $user->email) }}"
                           class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm">
                    @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Username</label>
                    <p class="mt-1 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-600">{{ $user->username }}</p>
                    <p class="mt-1 text-xs text-gray-500">Username digunakan untuk masuk dan tidak dapat diubah.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Peran</label>
                    <p class="mt-1 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm text-gray-600">{{ $roleLabel }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-kwamki-forest">Keamanan</h2>
            <p class="mt-1 text-sm text-gray-600">Kosongkan jika tidak ingin mengganti password.</p>

            <div class="mt-6 space-y-4">
                <div>
                    <label for="current_password" class="block text-sm font-medium text-gray-700">Password saat ini</label>
                    <div class="relative mt-1">
                        <input type="password" name="current_password" id="current_password" autocomplete="current-password"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 text-sm">
                        <button type="button"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700"
                                data-password-toggle
                                aria-label="Tampilkan password"
                                aria-pressed="false">
                            <svg data-icon="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg data-icon="hide" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858 3.029a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('current_password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password baru</label>
                    <div class="relative mt-1">
                        <input type="password" name="password" id="password" autocomplete="new-password"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 text-sm">
                        <button type="button"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700"
                                data-password-toggle
                                aria-label="Tampilkan password"
                                aria-pressed="false">
                            <svg data-icon="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg data-icon="hide" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858 3.029a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                    @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi password baru</label>
                    <div class="relative mt-1">
                        <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password"
                               class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-10 text-sm">
                        <button type="button"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-gray-700"
                                data-password-toggle
                                aria-label="Tampilkan password"
                                aria-pressed="false">
                            <svg data-icon="show" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            <svg data-icon="hide" class="hidden h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858 3.029a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="rounded-lg bg-kwamki-forest px-4 py-2 text-sm font-semibold text-white hover:bg-kwamki-forest-dark">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.dashboard') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    document.querySelectorAll('[data-password-toggle]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const input = btn.closest('.relative').querySelector('input');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            btn.querySelector('[data-icon="show"]').classList.toggle('hidden', isHidden);
            btn.querySelector('[data-icon="hide"]').classList.toggle('hidden', !isHidden);
            btn.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Tampilkan password');
            btn.setAttribute('aria-pressed', isHidden ? 'true' : 'false');
        });
    });
})();
</script>
@endpush
