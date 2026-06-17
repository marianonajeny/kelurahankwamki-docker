@php
    $user = auth()->user();
    $isLurah = $user?->hasRole(\App\Models\User::ROLE_LURAH);
    $menuItems = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
        ['route' => 'admin.berita.index', 'label' => 'Berita', 'icon' => 'news'],
        ['route' => 'admin.pengumuman.index', 'label' => 'Pengumuman', 'icon' => 'bell'],
        ['route' => 'admin.galeri.index', 'label' => 'Galeri', 'icon' => 'image'],
        ['route' => 'admin.layanan.index', 'label' => 'Layanan', 'icon' => 'service'],
        ['route' => 'admin.permohonan.index', 'label' => 'Permohonan', 'icon' => 'inbox', 'badge_count' => $permohonanBadgeCount ?? 0],
        ['route' => 'admin.permohonan.kirim-whatsapp', 'label' => 'Kirim WhatsApp', 'icon' => 'whatsapp', 'badge_count' => $kirimWhatsappCount ?? 0],
        ['route' => 'admin.pesan.index', 'label' => 'Pesan', 'icon' => 'mail', 'badge_count' => $pesanBaruCount ?? 0],
    ];
    if ($isLurah) {
        $menuItems = array_values(array_filter($menuItems, fn ($item) => in_array($item['route'], [
            'admin.dashboard',
            'admin.berita.index',
            'admin.pengumuman.index',
            'admin.galeri.index',
            'admin.permohonan.index',
        ], true)));
    }
@endphp

<aside class="fixed inset-y-0 left-0 z-50 flex h-screen w-64 flex-col overflow-hidden bg-kwamki-forest-dark text-white shadow-lg">
    <div class="flex h-16 shrink-0 items-center gap-3 border-b border-white/10 px-5">
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-kwamki-gold text-sm font-bold text-kwamki-forest-dark">KW</div>
        <div>
            <p class="text-xs text-white/60">Panel Admin</p>
            <p class="text-sm font-bold leading-tight">Kelurahan Kwamki</p>
        </div>
    </div>

    <nav class="min-h-0 flex-1 space-y-1 overflow-y-auto p-4" aria-label="Menu admin">
        @foreach($menuItems as $item)
            @php
                $active = request()->routeIs($item['route']);

                if ($item['route'] === 'admin.permohonan.index') {
                    $active = request()->routeIs('admin.permohonan.*')
                        && ! request()->routeIs('admin.permohonan.kirim-whatsapp');
                } elseif ($item['route'] === 'admin.permohonan.kirim-whatsapp') {
                    $active = request()->routeIs('admin.permohonan.kirim-whatsapp');
                } elseif (str_ends_with($item['route'], '.index')) {
                    $active = $active || request()->routeIs(str_replace('.index', '.*', $item['route']));
                }

                if ($item['route'] === 'admin.pesan.index') {
                    $active = $active || request()->routeIs('admin.pesan.*');
                }

                $activeClass = ($item['route'] === 'admin.permohonan.kirim-whatsapp' && $active)
                    ? 'bg-whatsapp text-white'
                    : ($active ? 'bg-kwamki-gold text-kwamki-forest-dark' : 'text-white/80 hover:bg-white/10 hover:text-white');
            @endphp
            <a href="{{ route($item['route'], $item['params'] ?? []) }}"
               class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $activeClass }}">
                @if($item['icon'] === 'home')
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                @elseif($item['icon'] === 'news')
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                @elseif($item['icon'] === 'bell')
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @elseif($item['icon'] === 'image')
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                @elseif($item['icon'] === 'inbox')
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V7a2 2 0 00-2-2H6a2 2 0 00-2 2v6m16 0v4a2 2 0 01-2 2H6a2 2 0 01-2-2v-4m16 0h-4.586a1 1 0 00-.707.293l-1.414 1.414a1 1 0 01-.707.293h-2.172a1 1 0 01-.707-.293L8.293 13.293A1 1 0 007.586 13H4"/></svg>
                @elseif($item['icon'] === 'mail')
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                @elseif($item['icon'] === 'whatsapp')
                    <svg class="h-5 w-5 shrink-0 {{ $active ? 'text-white' : 'text-whatsapp' }}" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.435 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                @else
                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                @endif
                {{ $item['label'] }}
                @if($item['route'] === 'admin.permohonan.index')
                    <span id="permohonan-badge"
                          class="ml-auto min-w-[1.25rem] rounded-full bg-amber-500 px-1.5 py-0.5 text-center text-[10px] font-bold leading-none text-kwamki-forest-dark {{ ($item['badge_count'] ?? 0) > 0 ? '' : 'hidden' }}">{{ $item['badge_count'] ?? 0 }}</span>
                @elseif(($item['badge_count'] ?? 0) > 0)
                    <span class="ml-auto min-w-[1.25rem] rounded-full bg-amber-500 px-1.5 py-0.5 text-center text-[10px] font-bold leading-none text-kwamki-forest-dark">{{ $item['badge_count'] }}</span>
                @endif
            </a>
        @endforeach

        @php
            $accountActive = request()->routeIs('admin.pengaturan-akun.*');
        @endphp
        <a href="{{ route('admin.pengaturan-akun.edit') }}"
           class="mt-4 flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ $accountActive ? 'bg-kwamki-gold text-kwamki-forest-dark' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            Pengaturan Akun
        </a>
    </nav>

    <div class="shrink-0 space-y-1 border-t border-white/10 p-4">
        <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-white/70 transition hover:bg-white/10 hover:text-white">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            Lihat Website
        </a>
        <form method="POST" action="{{ route('logout') }}" class="m-0">
            @csrf
            <button type="submit" class="flex w-full items-center gap-3 rounded-lg bg-red-500/20 px-3 py-2.5 text-left text-sm font-medium text-red-300 transition hover:bg-red-500/30 hover:text-red-200">
                <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>
