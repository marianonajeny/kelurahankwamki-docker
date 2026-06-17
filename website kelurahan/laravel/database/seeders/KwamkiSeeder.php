<?php

namespace Database\Seeders;

use App\Models\Berita;
use App\Models\Galeri;
use App\Models\Pengaturan;
use App\Models\Pengumuman;
use App\Models\ProfilSection;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KwamkiSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Kwamki',
                'email' => 'admin@kelurahankwamki.my.id',
                'password' => Hash::make('Kwamki@2026'),
                'role' => User::ROLE_ADMIN,
            ]
        );

        User::updateOrCreate(
            ['username' => 'lurah'],
            [
                'name' => 'Kepala Kelurahan Kwamki',
                'email' => 'lurah@kelurahankwamki.my.id',
                'password' => Hash::make('KwamkiLurah@2026'),
                'role' => User::ROLE_LURAH,
            ]
        );

        $pengaturan = [
            'alamat' => 'Jl. Kwamki, Distrik Mimika Baru, Kabupaten Mimika, Papua Tengah 99952',
            'telepon' => '(0901) 123-4567',
            'email' => 'info@kelurahankwamki.my.id',
            'jam_layanan' => 'Senin–Jumat, 08.00–16.00 WIT',
            'whatsapp' => '6281234567890',
            'instagram' => 'https://instagram.com/kelurahan_kwamki',
        ];

        foreach ($pengaturan as $key => $value) {
            Pengaturan::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        $profil = [
            ['key' => 'ringkasan', 'judul' => 'Tentang Kwamki', 'urutan' => 1, 'konten' => 'Kelurahan Kwamki merupakan salah satu kelurahan di Distrik Mimika Baru, Kabupaten Mimika, Provinsi Papua Tengah. Kami berkomitmen memberikan pelayanan publik yang transparan, akuntabel, dan berorientasi pada kebutuhan masyarakat.'],
            ['key' => 'sejarah', 'judul' => 'Sejarah', 'urutan' => 2, 'konten' => "Kelurahan Kwamki berkembang seiring pertumbuhan kawasan Mimika Baru sebagai pusat pemukiman dan aktivitas masyarakat di Kabupaten Mimika.\n\nSebagai kelurahan, Kwamki memiliki peran strategis dalam koordinasi pelayanan administrasi kependudukan, pembinaan kemasyarakatan, serta pelaksanaan program pembangunan daerah."],
            ['key' => 'visi', 'judul' => 'Visi', 'urutan' => 3, 'konten' => 'Terwujudnya Kelurahan Kwamki yang mandiri, sejahtera, dan berdaya saing melalui pelayanan publik yang prima.'],
            ['key' => 'misi', 'judul' => 'Misi', 'urutan' => 4, 'konten' => "1. Meningkatkan kualitas pelayanan administrasi kependudukan.\n2. Mendorong partisipasi masyarakat dalam pembangunan kelurahan.\n3. Mengembangkan potensi ekonomi lokal secara berkelanjutan.\n4. Menjaga keharmonisan dan keamanan lingkungan."],
            ['key' => 'struktur', 'judul' => 'Struktur Organisasi', 'urutan' => 5, 'konten' => "Lurah Kelurahan Kwamki\nSekretaris Kelurahan\nKasi Pemerintahan\nKasi Kesejahteraan Rakyat\nKasi Pelayanan\nStaf Kelurahan"],
        ];

        foreach ($profil as $item) {
            ProfilSection::updateOrCreate(['key' => $item['key']], $item);
        }
        $beritas = [
            [
                'judul' => 'Musyawarah Perencanaan Pembangunan Kelurahan 2026',
                'slug' => 'musyawarah-perencanaan-pembangunan-2026',
                'ringkasan' => 'Warga Kwamki berpartisipasi aktif dalam musyawarah perencanaan pembangunan tahun 2026.',
                'isi' => "Kelurahan Kwamki menyelenggarakan Musyawarah Perencanaan Pembangunan (Musrenbang) yang dihadiri perwakilan RT/RW, tokoh masyarakat, dan pemangku kepentingan.\n\nKegiatan ini menjadi forum untuk menyampaikan aspirasi pembangunan infrastruktur, kesehatan, pendidikan, dan pemberdayaan ekonomi masyarakat.",
                'published_at' => now()->subDays(3),
            ],
            [
                'judul' => 'Posyandu Terpadu Gelar Pemeriksaan Balita',
                'slug' => 'posyandu-terpadu-pemeriksaan-balita',
                'ringkasan' => 'Kegiatan rutin posyandu untuk memantau kesehatan balita dan ibu hamil.',
                'isi' => "Posyandu terpadu Kelurahan Kwamki menggelar pemeriksaan kesehatan balita dan ibu hamil. Kegiatan ini melibatkan kader posyandu dan tenaga kesehatan.\n\nProgram ini bertujuan menekan angka stunting dan meningkatkan derajat kesehatan ibu dan anak.",
                'published_at' => now()->subDays(10),
            ],
            [
                'judul' => 'Gotong Royong Bersih Lingkungan Kwamki',
                'slug' => 'gotong-royong-bersih-lingkungan',
                'ringkasan' => 'Warga bersama aparat kelurahan melakukan kerja bakti kebersihan lingkungan.',
                'isi' => "Dalam rangka menjaga kebersihan dan keindahan lingkungan, Kelurahan Kwamki menginisiasi kegiatan gotong royong bersih-bersih di beberapa titik permukiman.\n\nKegiatan ini diharapkan menumbuhkan kesadaran warga akan pentingnya pengelolaan sampah yang baik.",
                'published_at' => now()->subDays(18),
            ],
            [
                'judul' => 'Sosialisasi Pencegahan Stunting di Wilayah Kwamki',
                'slug' => 'sosialisasi-pencegahan-stunting',
                'ringkasan' => 'Program edukasi gizi seimbang untuk keluarga dengan balita.',
                'isi' => "Tim kelurahan bersama puskesmas setempat menyelenggarakan sosialisasi pencegahan stunting. Materi meliputi gizi seimbang, ASI eksklusif, dan pemantauan tumbuh kembang anak.",
                'published_at' => now()->subDays(25),
            ],
        ];

        foreach ($beritas as $item) {
            Berita::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['is_published' => true]));
        }

        $pengumumans = [
            [
                'judul' => 'Jadwal Pelayanan Administrasi Bulan Ini',
                'isi' => "Diberitahukan kepada seluruh warga Kelurahan Kwamki bahwa pelayanan administrasi kependudukan dibuka setiap hari kerja pukul 08.00–16.00 WIT.\n\nMohon membawa dokumen lengkap sesuai jenis layanan yang diajukan.",
                'tanggal_mulai' => now(),
                'tanggal_akhir' => now()->addMonth(),
            ],
            [
                'judul' => 'Pendaftaran Bantuan Sosial Tahap Berikutnya',
                'isi' => "Warga yang belum terdaftar dalam data bantuan sosial dimohon untuk melapor ke kantor kelurahan dengan membawa KTP dan Kartu Keluarga.\n\nPendaftaran dibuka hingga akhir bulan.",
                'tanggal_mulai' => now()->subDays(2),
            ],
        ];

        foreach ($pengumumans as $item) {
            Pengumuman::updateOrCreate(['judul' => $item['judul']], array_merge($item, ['is_published' => true]));
        }

        $galeri = [
            ['judul' => 'Musrenbang 2026', 'kategori' => 'kegiatan', 'urutan' => 1],
            ['judul' => 'Posyandu Balita', 'kategori' => 'kesehatan', 'urutan' => 2],
            ['judul' => 'Kerja Bakti Lingkungan', 'kategori' => 'kegiatan', 'urutan' => 3],
            ['judul' => 'Sosialisasi Stunting', 'kategori' => 'kesehatan', 'urutan' => 4],
            ['judul' => 'Rapat Koordinasi RT/RW', 'kategori' => 'pemerintahan', 'urutan' => 5],
            ['judul' => 'Pelayanan Administrasi', 'kategori' => 'pelayanan', 'urutan' => 6],
        ];

        foreach ($galeri as $item) {
            Galeri::updateOrCreate(['judul' => $item['judul']], array_merge($item, [
                'gambar' => '',
                'is_published' => true,
            ]));
        }
    }
}
