<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $beritas = [
            [
                'judul' => 'Musyawarah Perencanaan Pembangunan Kelurahan 2026',
                'slug' => 'musyawarah-perencanaan-pembangunan-2026',
                'ringkasan' => 'Warga Kwamki berpartisipasi aktif dalam musyawarah perencanaan pembangunan tahun 2026.',
                'isi' => "Kelurahan Kwamki menyelenggarakan Musyawarah Perencanaan Pembangunan (Musrenbang) yang dihadiri perwakilan RT/RW, tokoh masyarakat, dan pemangku kepentingan.\n\nKegiatan ini menjadi forum untuk menyampaikan aspirasi pembangunan infrastruktur, kesehatan, pendidikan, dan pemberdayaan ekonomi masyarakat.",
                'published_at' => now()->subDays(3),
                'is_published' => true,
            ],
            [
                'judul' => 'Posyandu Terpadu Gelar Pemeriksaan Balita',
                'slug' => 'posyandu-terpadu-pemeriksaan-balita',
                'ringkasan' => 'Kegiatan rutin posyandu untuk memantau kesehatan balita dan ibu hamil.',
                'isi' => "Posyandu terpadu Kelurahan Kwamki menggelar pemeriksaan kesehatan balita dan ibu hamil. Kegiatan ini melibatkan kader posyandu dan tenaga kesehatan.\n\nProgram ini bertujuan menekan angka stunting dan meningkatkan derajat kesehatan ibu dan anak.",
                'published_at' => now()->subDays(10),
                'is_published' => true,
            ],
            [
                'judul' => 'Kerja Bakti Bersih Lingkungan Kwamki',
                'slug' => 'kerjabakti',
                'ringkasan' => 'Warga bersama aparat kelurahan melakukan kerja bakti kebersihan lingkungan.',
                'isi' => "Dalam rangka menjaga kebersihan dan keindahan lingkungan, Kelurahan Kwamki menginisiasi kegiatan kerja bakti bersih-bersih di beberapa titik permukiman.\n\nKegiatan ini diharapkan menumbuhkan kesadaran warga akan pentingnya pengelolaan sampah yang baik.",
                'published_at' => now()->subDays(18),
                'is_published' => true,
            ],
            [
                'judul' => 'Sosialisasi Pencegahan Stunting di Wilayah Kwamki',
                'slug' => 'sosialisasi-pencegahan-stunting',
                'ringkasan' => 'Program edukasi gizi seimbang untuk keluarga dengan balita.',
                'isi' => "Tim kelurahan bersama puskesmas setempat menyelenggarakan sosialisasi pencegahan stunting. Materi meliputi gizi seimbang, ASI eksklusif, dan pemantauan tumbuh kembang anak.",
                'published_at' => now()->subDays(25),
                'is_published' => true,
            ],
        ];

        $now = now();

        foreach ($beritas as $item) {
            $exists = DB::table('beritas')->where('slug', $item['slug'])->exists();

            if ($exists) {
                DB::table('beritas')
                    ->where('slug', $item['slug'])
                    ->update([
                        'judul' => $item['judul'],
                        'ringkasan' => $item['ringkasan'],
                        'isi' => $item['isi'],
                        'published_at' => $item['published_at'],
                        'is_published' => $item['is_published'],
                        'updated_at' => $now,
                    ]);
            } else {
                DB::table('beritas')->insert(array_merge($item, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }

    public function down(): void
    {
        DB::table('beritas')->whereIn('slug', [
            'musyawarah-perencanaan-pembangunan-2026',
            'posyandu-terpadu-pemeriksaan-balita',
            'kerjabakti',
            'sosialisasi-pencegahan-stunting',
        ])->delete();
    }
};
