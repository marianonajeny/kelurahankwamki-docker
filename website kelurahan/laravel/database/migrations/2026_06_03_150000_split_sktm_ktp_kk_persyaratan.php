<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private const SLUG = 'surat-keterangan-tidak-mampu-sktm';

    private const PERSYARATAN_LAMA = <<<'TEXT'
- Fotokopi KTP dan KK
- Surat pengantar RT/RW
- Berkas pendukung sesuai keperluan (mis. surat dari sekolah untuk bantuan pendidikan)
TEXT;

    private const PERSYARATAN_BARU = <<<'TEXT'
- Fotokopi KTP
- Fotokopi KK
- Surat pengantar RT/RW
- Berkas pendukung sesuai keperluan (mis. surat dari sekolah untuk bantuan pendidikan)
TEXT;

    public function up(): void
    {
        DB::table('layanans')
            ->where('slug', self::SLUG)
            ->update([
                'persyaratan' => self::PERSYARATAN_BARU,
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('layanans')
            ->where('slug', self::SLUG)
            ->update([
                'persyaratan' => self::PERSYARATAN_LAMA,
                'updated_at' => now(),
            ]);
    }
};
