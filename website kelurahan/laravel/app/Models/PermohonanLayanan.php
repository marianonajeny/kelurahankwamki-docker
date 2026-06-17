<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;

#[Fillable([
    'layanan_id',
    'nomor',
    'nama',
    'nik',
    'no_hp',
    'email',
    'alamat',
    'tempat_lahir',
    'tanggal_lahir',
    'jenis_kelamin',
    'agama',
    'status_perkawinan',
    'pekerjaan',
    'tahun_domisili',
    'anak_ke',
    'nama_ayah',
    'nik_ayah',
    'nama_ibu',
    'nik_ibu',
    'pendidikan',
    'kelurahan_tujuan',
    'kecamatan_tujuan',
    'kota_tujuan',
    'provinsi_tujuan',
    'tanggal_pindah',
    'alasan_pindah',
    'pengikut',
    'keperluan',
    'lampiran',
    'lampiran_berkas',
    'status',
    'catatan_admin',
    'diproses_at',
    'selesai_at',
    'diverifikasi_admin_at',
    'diverifikasi_lurah_at',
    'ditandatangani_at',
    'nomor_surat',
    'tanggal_surat',
    'surat_draft_html',
    'surat_terbit_path',
    'surat_diterbitkan_at',
    'ttd_gambar_path',
    'ttd_penandatangan_nip',
])]
class PermohonanLayanan extends Model
{
    protected function casts(): array
    {
        return [
            'lampiran_berkas' => 'array',
            'tanggal_lahir' => 'date',
            'tanggal_pindah' => 'date',
            'diproses_at' => 'datetime',
            'diverifikasi_admin_at' => 'datetime',
            'diverifikasi_lurah_at' => 'datetime',
            'ditandatangani_at' => 'datetime',
            'selesai_at' => 'datetime',
            'tanggal_surat' => 'date',
            'surat_diterbitkan_at' => 'datetime',
        ];
    }

    public const STATUS_DIAJUKAN = 'diajukan';

    public const STATUS_DIPROSES_ADMIN = 'diproses_admin';

    public const STATUS_PERLU_PERBAIKAN_DATA = 'perlu_perbaikan_data';

    public const STATUS_TERVERIFIKASI_ADMIN = 'terverifikasi_admin';

    public const STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN = 'menunggu_verifikasi_kepala_kelurahan';

    public const STATUS_REVISI_DARI_KEPALA_KELURAHAN = 'revisi_dari_kepala_kelurahan';

    public const STATUS_DITANDATANGANI_KEPALA_KELURAHAN = 'ditandatangani_kepala_kelurahan';

    public const STATUS_SELESAI = 'selesai';

    public const STATUS_DITOLAK = 'ditolak';

    /**
     * @return list<string>
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_DIAJUKAN,
            self::STATUS_DIPROSES_ADMIN,
            self::STATUS_PERLU_PERBAIKAN_DATA,
            self::STATUS_TERVERIFIKASI_ADMIN,
            self::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN,
            self::STATUS_REVISI_DARI_KEPALA_KELURAHAN,
            self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN,
            self::STATUS_SELESAI,
            self::STATUS_DITOLAK,
        ];
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class);
    }

    public function scopeAntrianLurah(Builder $query): Builder
    {
        return $query->whereIn('status', [
            self::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN,
            self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN,
            self::STATUS_SELESAI,
        ]);
    }

    public function usesBiodataUmum(): bool
    {
        return in_array($this->layanan?->slug, [
            Layanan::SLUG_SKTM,
            Layanan::SLUG_DOMISILI,
            Layanan::SLUG_BELUM_MENIKAH,
            Layanan::SLUG_PINDAH,
        ], true);
    }

    public function isSuratResmi(): bool
    {
        return in_array($this->layanan?->slug, Layanan::slugsSuratResmi(), true);
    }

    public function hasSuratTerbit(): bool
    {
        return filled($this->surat_terbit_path);
    }

    public function hasSuratDraft(): bool
    {
        return filled($this->surat_draft_html);
    }

    public function hasSuratSiapTerbit(): bool
    {
        return filled($this->nomor_surat) && filled($this->tanggal_surat);
    }

    public function hasSuratUntukLurah(): bool
    {
        return $this->hasSuratTerbit()
            || $this->hasSuratDraft()
            || $this->hasSuratSiapTerbit();
    }

    public function perluKirimKeAntrianLurah(): bool
    {
        if (! $this->hasSuratTerbit()) {
            return false;
        }

        return ! in_array(self::normalizeStatus($this->status), [
            self::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN,
            self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN,
            self::STATUS_SELESAI,
            self::STATUS_DITOLAK,
        ], true);
    }

    public function canLurahView(): bool
    {
        return in_array(self::normalizeStatus($this->status), [
            self::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN,
            self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN,
            self::STATUS_SELESAI,
        ], true);
    }

    public function canKirimKeKepalaKelurahan(?Authenticatable $user): bool
    {
        if (! $user instanceof User || ! $user->isAdmin()) {
            return false;
        }

        if (! $this->hasSuratTerbit()) {
            return false;
        }

        $status = self::normalizeStatus($this->status);

        return in_array($status, [
            self::STATUS_DIPROSES_ADMIN,
            self::STATUS_TERVERIFIKASI_ADMIN,
            self::STATUS_REVISI_DARI_KEPALA_KELURAHAN,
        ], true);
    }

    public function canKirimSuratKeWhatsappWarga(): bool
    {
        if (! $this->hasSuratTerbit()) {
            return false;
        }

        return in_array(self::normalizeStatus($this->status), [
            self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN,
            self::STATUS_SELESAI,
        ], true);
    }

    public function suratPdfPublicUrl(): ?string
    {
        if (! $this->hasSuratTerbit()) {
            return null;
        }

        return asset('storage/'.$this->surat_terbit_path);
    }

    public function suratUnduhToken(): string
    {
        return substr(hash_hmac(
            'sha256',
            $this->id.'|'.$this->surat_terbit_path,
            (string) config('app.key'),
        ), 0, 32);
    }

    public function suratPdfDownloadUrl(): ?string
    {
        if (! $this->canKirimSuratKeWhatsappWarga()) {
            return null;
        }

        return route('surat.publik.unduh', [
            'permohonan' => $this->id,
            'token' => $this->suratUnduhToken(),
        ]);
    }

    public function whatsappTargetE164(): ?string
    {
        return self::normalizePhoneE164((string) $this->no_hp);
    }

    public function hasLampiranBerkas(): bool
    {
        return is_array($this->lampiran_berkas) && count($this->lampiran_berkas) > 0;
    }

    public function hasPhoneSuffix(string $digits4): bool
    {
        $hp = preg_replace('/\D/', '', $this->no_hp);

        return str_ends_with($hp, $digits4);
    }

    public function matchesPhone(string $input): bool
    {
        $stored = self::normalizePhoneE164((string) $this->no_hp);
        $provided = self::normalizePhoneE164($input);

        return $stored !== null && $provided !== null && $stored === $provided;
    }

    public static function normalizePhoneE164(string $phone): ?string
    {
        $hp = preg_replace('/\D/', '', $phone);
        if ($hp === '') {
            return null;
        }

        if (str_starts_with($hp, '0')) {
            $hp = '62'.substr($hp, 1);
        } elseif (! str_starts_with($hp, '62')) {
            $hp = '62'.$hp;
        }

        return $hp;
    }

    public static function normalizeStatus(?string $status): ?string
    {
        return match ($status) {
            'diproses' => self::STATUS_DIPROSES_ADMIN,
            default => $status,
        };
    }

    public static function statusLabel(?string $status): string
    {
        $status = self::normalizeStatus($status) ?? $status;

        return match ($status) {
            self::STATUS_DIAJUKAN => 'Diajukan',
            self::STATUS_DIPROSES_ADMIN, 'diproses' => 'Diproses Admin',
            self::STATUS_PERLU_PERBAIKAN_DATA => 'Perlu Perbaikan Data',
            self::STATUS_TERVERIFIKASI_ADMIN => 'Terverifikasi Admin',
            self::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN => 'Menunggu Verifikasi Kepala Kelurahan',
            self::STATUS_REVISI_DARI_KEPALA_KELURAHAN => 'Revisi dari Kepala Kelurahan',
            self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN => 'Ditandatangani Kepala Kelurahan',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DITOLAK => 'Ditolak',
            default => 'Error',
        };
    }

    public static function statusTone(?string $status): string
    {
        $status = self::normalizeStatus($status) ?? $status;

        return match ($status) {
            self::STATUS_SELESAI, self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN => 'success',
            self::STATUS_DITOLAK => 'danger',
            self::STATUS_DIPROSES_ADMIN, self::STATUS_TERVERIFIKASI_ADMIN, 'diproses' => 'info',
            default => 'warning',
        };
    }

    public function nextStatusFor(?Authenticatable $user): ?string
    {
        $status = self::normalizeStatus($this->status);
        if (! $user instanceof User) {
            return null;
        }

        if ($user->isAdmin()) {
            return match ($status) {
                self::STATUS_DIAJUKAN => self::STATUS_DIPROSES_ADMIN,
                self::STATUS_DIPROSES_ADMIN,
                self::STATUS_PERLU_PERBAIKAN_DATA,
                self::STATUS_REVISI_DARI_KEPALA_KELURAHAN => self::STATUS_TERVERIFIKASI_ADMIN,
                self::STATUS_TERVERIFIKASI_ADMIN => self::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN,
                default => null,
            };
        }

        if ($user->hasRole(User::ROLE_LURAH)) {
            return match ($status) {
                self::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN => self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN,
                self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN => self::STATUS_SELESAI,
                default => null,
            };
        }

        return null;
    }

    public function canUpdateStatusTo(string $status, ?Authenticatable $user): bool
    {
        $targetStatus = self::normalizeStatus($status);
        $currentStatus = self::normalizeStatus($this->status);
        if (! $user instanceof User) {
            return false;
        }

        if ($user->isAdmin()) {
            $allowedTargets = match ($currentStatus) {
                self::STATUS_DIAJUKAN => [self::STATUS_DIAJUKAN, self::STATUS_DIPROSES_ADMIN, self::STATUS_DITOLAK],
                self::STATUS_DIPROSES_ADMIN => [self::STATUS_DIPROSES_ADMIN, self::STATUS_PERLU_PERBAIKAN_DATA, self::STATUS_TERVERIFIKASI_ADMIN, self::STATUS_DITOLAK],
                self::STATUS_PERLU_PERBAIKAN_DATA => [self::STATUS_PERLU_PERBAIKAN_DATA, self::STATUS_DIPROSES_ADMIN, self::STATUS_TERVERIFIKASI_ADMIN, self::STATUS_DITOLAK],
                self::STATUS_TERVERIFIKASI_ADMIN => [self::STATUS_TERVERIFIKASI_ADMIN, self::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN, self::STATUS_DITOLAK],
                self::STATUS_REVISI_DARI_KEPALA_KELURAHAN => [self::STATUS_REVISI_DARI_KEPALA_KELURAHAN, self::STATUS_DIPROSES_ADMIN, self::STATUS_TERVERIFIKASI_ADMIN, self::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN, self::STATUS_DITOLAK],
                default => [self::STATUS_DITOLAK],
            };

            return in_array($targetStatus, $allowedTargets, true);
        }

        if ($user->hasRole(User::ROLE_LURAH)) {
            if (! in_array($targetStatus, [
                self::STATUS_REVISI_DARI_KEPALA_KELURAHAN,
                self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN,
                self::STATUS_SELESAI,
            ], true)) {
                return false;
            }

            return in_array($currentStatus, [
                self::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN,
                self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN,
            ], true);
        }

        return false;
    }

    public function canAdvance(?Authenticatable $user): bool
    {
        return $this->nextStatusFor($user) !== null;
    }

    public function labelForNextActionFor(?Authenticatable $user): ?string
    {
        $next = $this->nextStatusFor($user);

        return match ($next) {
            self::STATUS_DIPROSES_ADMIN => 'Mulai proses admin',
            self::STATUS_TERVERIFIKASI_ADMIN => 'Verifikasi admin',
            self::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN => 'Kirim ke kepala kelurahan',
            self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN => 'Verifikasi & TTD kepala kelurahan',
            self::STATUS_SELESAI => 'Selesaikan surat',
            default => null,
        };
    }

    public function applyStatus(string $status): void
    {
        $status = self::normalizeStatus($status);
        $this->status = $status;
        $now = now();

        switch ($status) {
            case self::STATUS_DIAJUKAN:
                $this->diproses_at = null;
                $this->diverifikasi_admin_at = null;
                $this->diverifikasi_lurah_at = null;
                $this->ditandatangani_at = null;
                $this->selesai_at = null;
                break;
            case self::STATUS_DIPROSES_ADMIN:
            case self::STATUS_PERLU_PERBAIKAN_DATA:
            case self::STATUS_REVISI_DARI_KEPALA_KELURAHAN:
                if (! $this->diproses_at) {
                    $this->diproses_at = $now;
                }
                $this->diverifikasi_admin_at = null;
                $this->diverifikasi_lurah_at = null;
                $this->ditandatangani_at = null;
                $this->selesai_at = null;
                break;
            case self::STATUS_TERVERIFIKASI_ADMIN:
            case self::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN:
                if (! $this->diproses_at) {
                    $this->diproses_at = $now;
                }
                $this->diverifikasi_admin_at = $now;
                $this->diverifikasi_lurah_at = null;
                $this->ditandatangani_at = null;
                $this->selesai_at = null;
                break;
            case self::STATUS_DITANDATANGANI_KEPALA_KELURAHAN:
                if (! $this->diproses_at) {
                    $this->diproses_at = $now;
                }
                if (! $this->diverifikasi_admin_at) {
                    $this->diverifikasi_admin_at = $now;
                }
                $this->diverifikasi_lurah_at = $now;
                $this->ditandatangani_at = $now;
                $this->selesai_at = null;
                break;
            case self::STATUS_SELESAI:
            case self::STATUS_DITOLAK:
                if (! $this->diproses_at) {
                    $this->diproses_at = $now;
                }
                if ($status === self::STATUS_SELESAI) {
                    if (! $this->diverifikasi_admin_at) {
                        $this->diverifikasi_admin_at = $now;
                    }
                    if (! $this->diverifikasi_lurah_at) {
                        $this->diverifikasi_lurah_at = $now;
                    }
                    if (! $this->ditandatangani_at) {
                        $this->ditandatangani_at = $now;
                    }
                }
                $this->selesai_at = $now;
                break;
        }
    }

    public function purgeFilesAndDelete(): void
    {
        $disk = Storage::disk('public');

        if (filled($this->lampiran) && $disk->exists($this->lampiran)) {
            $disk->delete($this->lampiran);
        }

        if (is_array($this->lampiran_berkas)) {
            foreach ($this->lampiran_berkas as $berkas) {
                $path = $berkas['path'] ?? null;
                if (filled($path) && $disk->exists($path)) {
                    $disk->delete($path);
                }
            }
        }

        if (filled($this->surat_terbit_path) && $disk->exists($this->surat_terbit_path)) {
            $disk->delete($this->surat_terbit_path);
        }

        if (filled($this->ttd_gambar_path) && $disk->exists($this->ttd_gambar_path)) {
            $disk->delete($this->ttd_gambar_path);
        }

        $this->delete();
    }

    public static function generateNomor(): string
    {
        $prefix = 'KW-'.now()->format('Ymd').'-';

        $last = static::query()
            ->where('nomor', 'like', $prefix.'%')
            ->lockForUpdate()
            ->orderByDesc('id')
            ->value('nomor');

        $seq = 1;
        if ($last !== null) {
            $suffix = substr($last, strlen($prefix));
            $seq = max(1, (int) $suffix + 1);
        }

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
