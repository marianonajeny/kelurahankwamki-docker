<header class="sticky top-0 z-40 border-b border-gray-200 bg-white/95 px-4 py-4 shadow-sm backdrop-blur sm:px-6 lg:px-8">
    <div class="flex items-center justify-between">
        <div>
            @isset($pageTitle)
                <h1 class="text-lg font-bold text-kwamki-forest-dark">{{ $pageTitle }}</h1>
            @endisset
            @isset($breadcrumb)
                <nav class="mt-1 text-sm text-gray-500" aria-label="Breadcrumb">
                    @foreach($breadcrumb as $label => $url)
                        @if($url)
                            <a href="{{ $url }}" class="hover:text-kwamki-forest">{{ $label }}</a>
                            <span class="mx-1">/</span>
                        @else
                            <span class="text-kwamki-forest">{{ $label }}</span>
                        @endif
                    @endforeach
                </nav>
            @endisset
        </div>
        <div class="text-right text-sm">
            <a href="{{ route('admin.pengaturan-akun.edit') }}" class="font-medium text-kwamki-forest-dark hover:text-kwamki-ocean hover:underline">{{ auth()->user()->name }}</a>
            <p class="text-xs font-medium text-kwamki-ocean">
                {{ auth()->user()?->hasRole(\App\Models\User::ROLE_LURAH) ? 'Kepala Kelurahan' : 'Administrator' }}
            </p>
            <p class="text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</p>
        </div>
    </div>
</header>
