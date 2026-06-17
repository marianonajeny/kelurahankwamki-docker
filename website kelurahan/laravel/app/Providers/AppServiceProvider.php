<?php

namespace App\Providers;

use App\Models\PesanKontak;
use App\Models\PermohonanLayanan;
use App\Models\Pengaturan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Carbon::setLocale('id');
        Paginator::useTailwind();

        View::share('siteHeroImage', asset('images/kantor-kelurahan-kwamki.jpg'));
        View::share('sitePlaceholderImage', asset('images/kantor-kelurahan-kwamki.jpg'));

        View::composer(['components.footer', 'layouts.app'], function ($view) {
            $view->with('pengaturan', Pengaturan::allCached());
        });

        $adminRoleComposer = function ($view): void {
            $user = Auth::user();
            $view->with([
                'canManageKonten' => $user?->isAdmin() === true,
                'isLurah' => $user?->hasRole(User::ROLE_LURAH) === true,
            ]);
        };

        View::composer(['admin.*', 'components.admin.*'], $adminRoleComposer);

        View::composer('components.admin.sidebar', function ($view) {
            $user = Auth::user();
            $isLurah = $user?->hasRole(User::ROLE_LURAH) ?? false;

            if ($isLurah) {
                $permohonanBadgeCount = PermohonanLayanan::where(
                    'status',
                    PermohonanLayanan::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN
                )->count();
                $permohonanBaruCount = 0;
                $suratDitandatanganiCount = 0;
            } else {
                $permohonanBaruCount = PermohonanLayanan::whereIn('status', [
                    PermohonanLayanan::STATUS_DIAJUKAN,
                    PermohonanLayanan::STATUS_REVISI_DARI_KEPALA_KELURAHAN,
                ])->count();
                $suratDitandatanganiCount = PermohonanLayanan::where(
                    'status',
                    PermohonanLayanan::STATUS_DITANDATANGANI_KEPALA_KELURAHAN
                )->count();
                $permohonanBadgeCount = $permohonanBaruCount + $suratDitandatanganiCount;
            }

            $view->with([
                'permohonanBaruCount' => $permohonanBaruCount,
                'suratDitandatanganiCount' => $suratDitandatanganiCount,
                'permohonanBadgeCount' => $permohonanBadgeCount,
                'kirimWhatsappCount' => $suratDitandatanganiCount,
                'pesanBaruCount' => PesanKontak::unreadCount(),
                'showSuratDitandatanganiNotif' => ! $isLurah,
            ]);
        });

        View::composer('layouts.admin', function ($view) {
            $user = Auth::user();
            $isLurah = $user?->hasRole(User::ROLE_LURAH) ?? false;
            $suratDitandatanganiCount = $isLurah
                ? 0
                : PermohonanLayanan::where('status', PermohonanLayanan::STATUS_DITANDATANGANI_KEPALA_KELURAHAN)->count();

            $menungguVerifikasiLurahCount = $isLurah
                ? PermohonanLayanan::where('status', PermohonanLayanan::STATUS_MENUNGGU_VERIFIKASI_KEPALA_KELURAHAN)->count()
                : 0;

            $view->with([
                'suratDitandatanganiCount' => $suratDitandatanganiCount,
                'showSuratDitandatanganiNotif' => ! $isLurah && $suratDitandatanganiCount > 0,
                'menungguVerifikasiLurahCount' => $menungguVerifikasiLurahCount,
                'showAntrianLurahNotif' => $isLurah && $menungguVerifikasiLurahCount > 0,
                'canManageKonten' => $user?->isAdmin() === true,
                'isLurah' => $isLurah,
            ]);
        });
    }
}
