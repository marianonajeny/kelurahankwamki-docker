<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const SLUG = 'surat-keterangan-domisili';

    private const PERSYARATAN_LAMA = <<<'TEXT'
- Fotokopi KTP pemohon
- Fotokopi Kartu Keluarga (KK)
- Surat pengantar RT/RW setempat
- Mengisi formulir permohonan di loket
TEXT;

    private const PERSYARATAN_BARU = <<<'TEXT'
- Fotokopi KTP pemohon
- Fotokopi Kartu Keluarga (KK)
- Surat pengantar RT/RW setempat
TEXT;

    private const ALUR_LAMA = <<<'TEXT'
1. Menyiapkan persyaratan lengkap
2. Datang ke loket pelayanan kelurahan pada jam kerja
3. Petugas memverifikasi identitas dan kelengkapan berkas
4. Surat keterangan domisili diterbitkan apabila syarat terpenuhi
TEXT;

    private const ALUR_BARU = <<<'TEXT'
1. Menyiapkan berkas persyaratan (scan/foto) sesuai daftar
2. Mengajukan permohonan melalui formulir online di website Kelurahan Kwamki
3. Petugas memverifikasi kelengkapan berkas dan data permohonan
4. Surat keterangan domisili diterbitkan setelah proses verifikasi selesai
TEXT;

    public function up(): void
    {
        DB::table('layanans')
            ->where('slug', self::SLUG)
            ->update([
                'persyaratan' => self::PERSYARATAN_BARU,
                'alur' => self::ALUR_BARU,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('layanans')
            ->where('slug', self::SLUG)
            ->update([
                'persyaratan' => self::PERSYARATAN_LAMA,
                'alur' => self::ALUR_LAMA,
                'updated_at' => now(),
            ]);
    }
};
