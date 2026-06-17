<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('galeris')->whereIn('judul', [
            'monyet habis putus tapi bohong',
            'cewenya adrian',
            'ini adrian',
        ])->delete();

        $now = now();
        $items = [
            ['judul' => 'Musrenbang 2026', 'kategori' => 'kegiatan', 'urutan' => 1],
            ['judul' => 'Posyandu Balita', 'kategori' => 'kesehatan', 'urutan' => 2],
            ['judul' => 'Kerja Bakti Lingkungan', 'kategori' => 'kegiatan', 'urutan' => 3],
            ['judul' => 'Sosialisasi Stunting', 'kategori' => 'kesehatan', 'urutan' => 4],
            ['judul' => 'Rapat Koordinasi RT/RW', 'kategori' => 'pemerintahan', 'urutan' => 5],
            ['judul' => 'Pelayanan Administrasi', 'kategori' => 'pelayanan', 'urutan' => 6],
        ];

        foreach ($items as $item) {
            DB::table('galeris')->updateOrInsert(
                ['judul' => $item['judul']],
                array_merge($item, [
                    'gambar' => '',
                    'is_published' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ])
            );
        }
    }

    public function down(): void
    {
        //
    }
};
