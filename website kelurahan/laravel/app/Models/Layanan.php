<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'nama',
    'slug',
    'deskripsi',
    'kategori',
    'persyaratan',
    'alur',
    'estimasi_waktu',
    'biaya',
    'petugas',
    'lokasi',
    'dokumen_url',
    'menerima_permohonan_online',
    'ikon',
    'link_url',
    'urutan',
    'is_active',
])]
class Layanan extends Model
{
    public const SLUG_SKTM = 'surat-keterangan-tidak-mampu-sktm';

    public const SLUG_DOMISILI = 'surat-keterangan-domisili';

    public const SLUG_BELUM_MENIKAH = 'surat-keterangan-belum-pernah-menikah';

    public const SLUG_KELAHIRAN = 'surat-keterangan-kelahiran';

    public const SLUG_PINDAH = 'surat-keterangan-pindah';

    public const SLUG_USAHA = 'surat-keterangan-usaha';

    public function permohonan(): HasMany
    {
        return $this->hasMany(PermohonanLayanan::class);
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'menerima_permohonan_online' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan');
    }

    public function scopeAcceptsOnlineApplications(Builder $query): Builder
    {
        return $query->where('menerima_permohonan_online', true);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function publicUrl(): string
    {
        return route('layanan.show', $this);
    }

    public function publicAjukanUrl(): string
    {
        return route('layanan.ajukan.form', $this);
    }

    /**
     * @return array<string, string>
     */
    public static function kategoriLabels(): array
    {
        return [
            'administrasi' => 'Administrasi Kependudukan',
            'sosial' => 'Sosial & Bantuan',
            'pengaduan' => 'Pengaduan & Partisipasi',
            'lainnya' => 'Layanan lainnya',
        ];
    }

    /**
     * @return list<string>
     */
    public static function slugsSuratResmi(): array
    {
        return [
            self::SLUG_SKTM,
            self::SLUG_DOMISILI,
            self::SLUG_BELUM_MENIKAH,
            self::SLUG_KELAHIRAN,
            self::SLUG_PINDAH,
        ];
    }

    public function pendingPermohonanCount(): int
    {
        return $this->permohonan()->where('status', PermohonanLayanan::STATUS_DIAJUKAN)->count();
    }

    /**
     * @return list<string>
     */
    public function persyaratanLines(): array
    {
        return collect(preg_split('/\r\n|\r|\n/', $this->persyaratan ?? ''))
            ->map(fn (string $line) => trim(ltrim(trim($line), '-• ')))
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @return list<array{key: string, label: string, wajib: bool}>
     */
    public function persyaratanBerkas(): array
    {
        $nonBerkasPattern = '/mengisi\s+formulir|datang\s+ke\s+(loket|kantor)|membawa\s+dokumen\s+asli|hadir\s+secara\s+langsung/i';
        $opsionalPattern = '/\(jika\s+ada\)|\(jika\s+relevan\)|opsional/i';

        $items = [];

        foreach ($this->persyaratanLines() as $line) {
            if (preg_match($nonBerkasPattern, $line)) {
                continue;
            }

            $items[] = [
                'key' => Str::slug($line),
                'label' => $line,
                'wajib' => ! preg_match($opsionalPattern, $line),
            ];
        }

        return $items;
    }
}
