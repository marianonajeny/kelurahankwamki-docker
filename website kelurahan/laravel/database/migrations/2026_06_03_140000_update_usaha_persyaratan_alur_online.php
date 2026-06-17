<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const SLUG = 'surat-keterangan-usaha';

    private const PERSYARATAN_LAMA = <<<'TEXT'
- Fotokopi KTP pemilik usaha
- Fotokopi KK
- Foto atau bukti lokasi usaha (jika ada)
- Mengisi formulir data usaha di loket
TEXT;

    private const PERSYARATAN_BARU = <<<'TEXT'
- Fotokopi KTP pemilik usaha
- Fotokopi KK
- Foto atau bukti lokasi usaha (jika ada)
TEXT;

    private const ALUR_LAMA = <<<'TEXT'
1. Melengkapi data usaha dan lokasi
2. Pengajuan ke loket
3. Kunjungan/pemeriksaan lokasi bila diperlukan
4. Surat keterangan usaha diterbitkan
TEXT;

    private const ALUR_BARU = <<<'TEXT'
1. Menyiapkan berkas persyaratan (scan/foto) dan data usaha
2. Mengajukan permohonan melalui formulir online di website Kelurahan Kwamki
3. Petugas memverifikasi data usaha; pemeriksaan lokasi dilakukan bila diperlukan
4. Surat keterangan usaha diterbitkan setelah proses verifikasi selesai
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
