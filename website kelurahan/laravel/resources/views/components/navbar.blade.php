@php
    $navItems = [
        ['route' => 'home', 'label' => 'Beranda'],
        ['route' => 'profil', 'label' => 'Profil'],
        ['route' => 'berita.index', 'label' => 'Berita'],
        ['route' => 'layanan', 'label' => 'Layanan', 'match' => ['layanan', 'layanan.show', 'layanan.ajukan.*', 'layanan.permohonan.sukses']],
        ['route' => 'pengumuman', 'label' => 'Pengumuman'],
        ['route' => 'galeri', 'label' => 'Galeri'],
        ['route' => 'layanan.cek-status', 'label' => 'Cek Status', 'match' => 'layanan.cek-status*'],
        ['route' => 'kontak', 'label' => 'Kontak'],
    ];
@endphp

<header class="sticky top-0 z-40 border-b border-kwamki-forest/10 bg-white/95 shadow-sm backdrop-blur">
    <div class="bg-kwamki-forest-dark text-xs text-white">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-1.5 sm:px-6 lg:px-8">
            <span>Pemerintah Kabupaten Mimika — Papua Tengah</span>
            <span class="hidden sm:inline">Kode Pos: 99952</span>
        </div>
    </div>

    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-full bg-kwamki-gold text-lg font-bold text-kwamki-forest-dark shadow">
                    KW
                </div>
                <div class="hidden sm:block">
                    <p class="text-xs font-medium uppercase tracking-wider text-kwamki-ocean">Website Resmi</p>
                    <p class="text-base font-bold leading-tight text-kwamki-forest">Kelurahan Kwamki</p>
                </div>
            </a>

            <nav class="hidden items-center gap-1 lg:flex" aria-label="Menu utama">
                @foreach($navItems as $item)
                    @php
                        $isActive = isset($item['match'])
                            ? request()->routeIs($item['match'])
                            : request()->routeIs($item['route'].'*') || request()->routeIs($item['route']);
                    @endphp
                    <a href="{{ route($item['route']) }}"
                       class="rounded-lg px-3 py-2 text-sm font-medium transition {{ $isActive ? 'bg-kwamki-gold text-kwamki-forest-dark' : 'text-gray-700 hover:bg-kwamki-sand hover:text-kwamki-forest' }}">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                @auth
                    @if(auth()->user()->canAccessAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="hidden rounded-lg bg-kwamki-gold px-3 py-2 text-xs font-semibold text-kwamki-forest-dark sm:inline-block">
                        Dashboard
                    </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="nav-login-btn hidden sm:inline-block">
                        Login
                    </a>
                @endauth

                <button type="button" id="mobile-menu-btn" class="rounded-lg p-2 text-kwamki-forest lg:hidden" aria-label="Buka menu" aria-expanded="false">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <nav id="mobile-menu" class="hidden border-t border-gray-100 pb-4 lg:hidden" aria-label="Menu mobile">
            @foreach($navItems as $item)
                @php
                    $isActive = isset($item['match'])
                        ? request()->routeIs($item['match'])
                        : request()->routeIs($item['route'].'*') || request()->routeIs($item['route']);
                @endphp
                <a href="{{ route($item['route']) }}"
                   class="block rounded-lg px-3 py-2 text-sm font-medium {{ $isActive ? 'bg-kwamki-gold text-kwamki-forest-dark' : 'text-gray-700 hover:bg-kwamki-sand' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
            @guest
                <a href="{{ route('login') }}" class="nav-login-btn-mobile">
                    Login
                </a>
            @endguest
            @auth
                @if(auth()->user()->canAccessAdmin())
                <a href="{{ route('admin.dashboard') }}" class="mt-2 block rounded-lg bg-kwamki-gold px-3 py-2 text-center text-sm font-semibold text-kwamki-forest-dark">
                    Dashboard
                </a>
                @endif
            @endauth
        </nav>
    </div>
</header>

<script>
document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
    const menu = document.getElementById('mobile-menu');
    const expanded = this.getAttribute('aria-expanded') === 'true';
    menu.classList.toggle('hidden');
    this.setAttribute('aria-expanded', !expanded);
});
</script>
