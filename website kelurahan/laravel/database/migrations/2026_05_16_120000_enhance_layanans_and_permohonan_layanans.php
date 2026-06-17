<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('layanans', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('nama');
            $table->string('kategori')->default('administrasi')->after('deskripsi');
            $table->text('persyaratan')->nullable()->after('kategori');
            $table->text('alur')->nullable()->after('persyaratan');
            $table->string('estimasi_waktu')->default('3 hari kerja')->after('alur');
            $table->string('biaya')->default('Gratis')->after('estimasi_waktu');
            $table->string('petugas')->nullable()->after('biaya');
            $table->string('lokasi')->nullable()->after('petugas');
            $table->string('dokumen_url')->nullable()->after('lokasi');
            $table->boolean('menerima_permohonan_online')->default(true)->after('dokumen_url');
        });

        $rows = DB::table('layanans')->orderBy('id')->get();
        foreach ($rows as $row) {
            $base = Str::slug($row->nama);
            $slug = $base.'-'.$row->id;
            $n = 1;
            while (DB::table('layanans')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                $slug = $base.'-'.$row->id.'-'.$n++;
            }
            DB::table('layanans')->where('id', $row->id)->update(['slug' => $slug]);
        }

        Schema::table('layanans', function (Blueprint $table) {
            $table->unique('slug');
        });

        DB::statement('ALTER TABLE layanans MODIFY slug VARCHAR(255) NOT NULL');

        Schema::create('permohonan_layanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('layanan_id')->constrained('layanans')->cascadeOnDelete();
            $table->string('nomor')->unique();
            $table->string('nama');
            $table->string('nik', 32);
            $table->string('no_hp', 32);
            $table->string('email')->nullable();
            $table->text('alamat');
            $table->text('keperluan');
            $table->string('lampiran')->nullable();
            $table->string('status')->default('diajukan');
            $table->text('catatan_admin')->nullable();
            $table->timestamp('diproses_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permohonan_layanans');

        Schema::table('layanans', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });

        Schema::table('layanans', function (Blueprint $table) {
            $table->dropColumn([
                'slug',
                'kategori',
                'persyaratan',
                'alur',
                'estimasi_waktu',
                'biaya',
                'petugas',
                'lokasi',
                'dokumen_url',
                'menerima_permohonan_online',
            ]);
        });
    }
};
