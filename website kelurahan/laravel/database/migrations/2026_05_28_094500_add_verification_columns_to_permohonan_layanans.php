<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('permohonan_layanans', function (Blueprint $table) {
            $table->timestamp('diverifikasi_admin_at')->nullable()->after('diproses_at');
            $table->timestamp('diverifikasi_lurah_at')->nullable()->after('diverifikasi_admin_at');
            $table->timestamp('ditandatangani_at')->nullable()->after('diverifikasi_lurah_at');
        });
    }

    public function down(): void
    {
        Schema::table('permohonan_layanans', function (Blueprint $table) {
            $table->dropColumn([
                'diverifikasi_admin_at',
                'diverifikasi_lurah_at',
                'ditandatangani_at',
            ]);
        });
    }
};
