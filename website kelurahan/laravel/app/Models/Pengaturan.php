<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class Pengaturan extends Model
{
    public const KEY_LURAH_JABATAN = 'lurah_jabatan';

    public const KEY_LURAH_NAMA = 'lurah_nama';

    public const KEY_LURAH_NIP = 'lurah_nip';

    public const KEY_LURAH_TTD_GAMBAR = 'lurah_ttd_gambar';

    protected $fillable = ['key', 'value'];

    public static function get(string $key, ?string $default = null): ?string
    {
        return Cache::remember('pengaturan.'.$key, 3600, function () use ($key, $default) {
            $row = static::query()->where('key', $key)->first();

            return $row?->value ?? $default;
        });
    }

    public static function allCached(): array
    {
        return Cache::remember('pengaturan.all', 3600, function () {
            return static::query()->pluck('value', 'key')->all();
        });
    }

    public static function set(string $key, ?string $value): void
    {
        static::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget('pengaturan.'.$key);
        Cache::forget('pengaturan.all');
    }

    /**
     * @return array{jabatan: string, nama: string, nip: string, gambar_path: ?string, gambar_url: ?string}
     */
    public static function ttdLurah(): array
    {
        $gambarPath = self::get(self::KEY_LURAH_TTD_GAMBAR);
        $gambarUrl = null;

        if (filled($gambarPath) && Storage::disk('public')->exists($gambarPath)) {
            $gambarUrl = asset('storage/'.$gambarPath);
        }

        return [
            'jabatan' => self::get(self::KEY_LURAH_JABATAN, 'Lurah Kwamki') ?? 'Lurah Kwamki',
            'nama' => self::get(self::KEY_LURAH_NAMA, '') ?? '',
            'nip' => self::get(self::KEY_LURAH_NIP, '') ?? '',
            'gambar_path' => $gambarPath,
            'gambar_url' => $gambarUrl,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function ttdViewDataForSurat(): array
    {
        $ttd = self::ttdLurah();
        $ttdSrc = null;

        if (filled($ttd['gambar_path']) && Storage::disk('public')->exists($ttd['gambar_path'])) {
            $absolute = Storage::disk('public')->path($ttd['gambar_path']);
            $mime = mime_content_type($absolute) ?: 'image/png';
            $ttdSrc = 'data:'.$mime.';base64,'.base64_encode((string) file_get_contents($absolute));
        }

        return [
            'jabatan_penandatangan' => $ttd['jabatan'],
            'nama_penandatangan' => filled($ttd['nama']) ? $ttd['nama'] : '(_____________________)',
            'nip_penandatangan' => filled($ttd['nip']) ? $ttd['nip'] : '—',
            'ttd_gambar_src' => $ttdSrc,
        ];
    }
}
