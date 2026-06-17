<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan_layanans', function (Blueprint $table) {
            if (! Schema::hasColumn('permohonan_layanans', 'nomor_surat')) {
                $table->string('nomor_surat', 100)->nullable()->after('catatan_admin');
            }
            if (! Schema::hasColumn('permohonan_layanans', 'tanggal_surat')) {
                $table->date('tanggal_surat')->nullable()->after('nomor_surat');
            }
            if (! Schema::hasColumn('permohonan_layanans', 'surat_draft_html')) {
                $table->longText('surat_draft_html')->nullable()->after('tanggal_surat');
            }
            if (! Schema::hasColumn('permohonan_layanans', 'surat_terbit_path')) {
                $table->string('surat_terbit_path')->nullable()->after('surat_draft_html');
            }
            if (! Schema::hasColumn('permohonan_layanans', 'surat_diterbitkan_at')) {
                $table->timestamp('surat_diterbitkan_at')->nullable()->after('surat_terbit_path');
            }
            if (! Schema::hasColumn('permohonan_layanans', 'surat_diterbitkan_oleh')) {
                $table->foreignId('surat_diterbitkan_oleh')->nullable()->after('surat_diterbitkan_at')
                    ->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('permohonan_layanans', function (Blueprint $table) {
            if (Schema::hasColumn('permohonan_layanans', 'surat_diterbitkan_oleh')) {
                $table->dropForeign(['surat_diterbitkan_oleh']);
            }

            $columns = array_filter([
                'nomor_surat',
                'tanggal_surat',
                'surat_draft_html',
                'surat_terbit_path',
                'surat_diterbitkan_at',
                'surat_diterbitkan_oleh',
            ], fn (string $column) => Schema::hasColumn('permohonan_layanans', $column));

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
