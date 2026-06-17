<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan_layanans', function (Blueprint $table) {
            $afterColumn = match (true) {
                Schema::hasColumn('permohonan_layanans', 'surat_diterbitkan_oleh') => 'surat_diterbitkan_oleh',
                Schema::hasColumn('permohonan_layanans', 'surat_diterbitkan_at') => 'surat_diterbitkan_at',
                Schema::hasColumn('permohonan_layanans', 'ditandatangani_at') => 'ditandatangani_at',
                default => 'catatan_admin',
            };

            if (! Schema::hasColumn('permohonan_layanans', 'ttd_gambar_path')) {
                $table->string('ttd_gambar_path')->nullable()->after($afterColumn);
            }
            if (! Schema::hasColumn('permohonan_layanans', 'ttd_penandatangan_nip')) {
                $table->string('ttd_penandatangan_nip', 30)->nullable()->after('ttd_gambar_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('permohonan_layanans', function (Blueprint $table) {
            $columns = array_filter([
                'ttd_gambar_path',
                'ttd_penandatangan_nip',
            ], fn (string $column) => Schema::hasColumn('permohonan_layanans', $column));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
