<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();

        DB::table('layanans')->updateOrInsert(
            ['slug' => 'surat-keterangan-domisili'],
            [
                'nama' => 'Surat Keterangan Domisili',
                'deskripsi' => 'Penerbitan surat keterangan tempat tinggal bagi warga yang berdomisili di Kelurahan Kwamki.',
                'kategori' => 'administrasi',
                'ikon' => 'home',
                'persyaratan' => "- Fotokopi KTP pemohon\n- Fotokopi Kartu Keluarga (KK)\n- Surat pengantar RT/RW setempat",
                'alur' => "1. Menyiapkan berkas persyaratan (scan/foto) sesuai daftar\n2. Mengajukan permohonan melalui formulir online di website Kelurahan Kwamki\n3. Petugas memverifikasi kelengkapan berkas dan data permohonan\n4. Surat keterangan domisili diterbitkan setelah proses verifikasi selesai",
                'estimasi_waktu' => '1 hari kerja',
                'biaya' => 'Gratis',
                'petugas' => 'Kasi Pelayanan / staf administrasi',
                'lokasi' => 'Loket pelayanan Kelurahan Kwamki',
                'menerima_permohonan_online' => true,
                'is_active' => true,
                'urutan' => 1,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );

        DB::table('layanans')->updateOrInsert(
            ['slug' => 'berita/kerjabakti'],
            [
                'nama' => 'maria',
                'deskripsi' => 'Layanan maria.',
                'kategori' => 'administrasi',
                'ikon' => 'document',
                'persyaratan' => "- Fotokopi KTP pemohon\n- Fotokopi Kartu Keluarga (KK)",
                'alur' => "1. Menyiapkan berkas persyaratan\n2. Mengajukan permohonan melalui formulir online di website Kelurahan Kwamki\n3. Petugas memverifikasi data permohonan",
                'estimasi_waktu' => '3 hari kerja',
                'biaya' => 'Gratis',
                'petugas' => 'Kasi Pelayanan',
                'lokasi' => 'Loket pelayanan Kelurahan Kwamki',
                'menerima_permohonan_online' => true,
                'is_active' => true,
                'urutan' => 99,
                'updated_at' => $now,
                'created_at' => $now,
            ]
        );
    }

    public function down(): void
    {
        DB::table('layanans')->whereIn('slug', ['surat-keterangan-domisili', 'berita/kerjabakti'])->delete();
    }
};
