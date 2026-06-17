@php
    $settings = $pengaturan ?? \App\Models\Pengaturan::allCached();
@endphp

<footer class="mt-auto border-t border-kwamki-forest/10 bg-kwamki-forest-dark text-white">
    <div class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid gap-8 md:grid-cols-3">
            <div>
                <h3 class="mb-3 text-lg font-bold text-kwamki-gold">Kelurahan Kwamki</h3>
                <p class="text-sm text-gray-300 leading-relaxed">
                    {{ $settings['alamat'] ?? 'Jl. Kwamki, Distrik Mimika Baru, Kabupaten Mimika, Papua Tengah 99952' }}
                </p>
            </div>
            <div>
                <h3 class="mb-3 text-lg font-bold text-kwamki-gold">Kontak</h3>
                <ul class="space-y-2 text-sm text-gray-300">
                    <li>Telp: {{ $settings['telepon'] ?? '(0901) —' }}</li>
                    <li>Email: {{ $settings['email'] ?? 'info@kelurahankwamki.my.id' }}</li>
                    <li>Jam Layanan: {{ $settings['jam_layanan'] ?? 'Senin–Jumat, 08.00–16.00 WIT' }}</li>
                </ul>
            </div>
            <div>
                <h3 class="mb-3 text-lg font-bold text-kwamki-gold">Tautan Cepat</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('profil') }}" class="text-gray-300 hover:text-kwamki-gold">Profil Kelurahan</a></li>
                    <li><a href="{{ route('layanan') }}" class="text-gray-300 hover:text-kwamki-gold">Layanan Publik</a></li>
                    <li><a href="{{ route('kontak') }}" class="text-gray-300 hover:text-kwamki-gold">Hubungi Kami</a></li>
                </ul>
            </div>
        </div>
        <div class="mt-8 border-t border-white/10 pt-6 text-center text-xs text-gray-400">
            &copy; {{ date('Y') }} Kelurahan Kwamki — Kabupaten Mimika, Papua Tengah. Hak Cipta Dilindungi.
        </div>
    </div>
</footer>
