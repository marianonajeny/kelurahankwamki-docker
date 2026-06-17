<?php

use App\Http\Controllers\Admin\BeritaController as AdminBeritaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GaleriController as AdminGaleriController;
use App\Http\Controllers\Admin\LayananController as AdminLayananController;
use App\Http\Controllers\Admin\PengaturanAkunController;
use App\Http\Controllers\Admin\PengaturanTtdController;
use App\Http\Controllers\Admin\PermohonanLayananController as AdminPermohonanLayananController;
use App\Http\Controllers\Admin\PermohonanNotifikasiController;
use App\Http\Controllers\Admin\PengumumanController as AdminPengumumanController;
use App\Http\Controllers\Admin\PesanKontakController as AdminPesanKontakController;
use App\Http\Controllers\Admin\SuratPermohonanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\GaleriController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\KontakController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PermohonanLayananController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SuratPublikController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
Route::get('/berita', [BeritaController::class, 'index'])->name('berita.index');
Route::get('/berita/{berita:slug}', [BeritaController::class, 'show'])->name('berita.show');
Route::get('/layanan', [LayananController::class, 'index'])->name('layanan');
Route::redirect(
    '/layanan/surat-keterangan-tidak-mampu',
    '/layanan/surat-keterangan-tidak-mampu-sktm',
    301
);
Route::redirect(
    '/layanan/surat-keterangan-tidak-mampu/ajukan',
    '/layanan/surat-keterangan-tidak-mampu-sktm/ajukan',
    301
);
Route::get('/layanan/cek-status', [PermohonanLayananController::class, 'cekStatus'])->name('layanan.cek-status');
Route::post('/layanan/cek-status', [PermohonanLayananController::class, 'cekStatusLookup'])
    ->middleware('throttle:5,1')
    ->name('layanan.cek-status.lookup');
Route::get('/layanan/permohonan-berhasil', [PermohonanLayananController::class, 'sukses'])->name('layanan.permohonan.sukses');
Route::get('/layanan/{layanan:slug}/ajukan', [PermohonanLayananController::class, 'create'])->name('layanan.ajukan.form');
Route::post('/layanan/{layanan:slug}/ajukan', [PermohonanLayananController::class, 'store'])->name('layanan.ajukan');
Route::get('/layanan/{layanan:slug}', [LayananController::class, 'show'])->name('layanan.show');
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman');
Route::get('/galeri', [GaleriController::class, 'index'])->name('galeri');
Route::get('/kontak', [KontakController::class, 'index'])->name('kontak');
Route::post('/kontak', [KontakController::class, 'store'])->name('kontak.store');

Route::get('/surat/{permohonan}/unduh/{token}', [SuratPublikController::class, 'unduh'])
    ->name('surat.publik.unduh');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::middleware('role:admin,lurah')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('berita', AdminBeritaController::class);
        Route::resource('pengumuman', AdminPengumumanController::class);
        Route::resource('galeri', AdminGaleriController::class);
        Route::resource('layanan', AdminLayananController::class)->except(['show']);

        Route::get('permohonan', [AdminPermohonanLayananController::class, 'index'])->name('permohonan.index');
        Route::get('permohonan/notifikasi', PermohonanNotifikasiController::class)->name('permohonan.notifikasi');
        Route::get('permohonan/pengaturan-ttd', [PengaturanTtdController::class, 'edit'])->name('permohonan.pengaturan-ttd.edit');
        Route::post('permohonan/pengaturan-ttd', [PengaturanTtdController::class, 'update'])->name('permohonan.pengaturan-ttd.update');
        Route::get('permohonan/kirim-whatsapp', [AdminPermohonanLayananController::class, 'kirimWhatsapp'])->name('permohonan.kirim-whatsapp');

        Route::get('permohonan/{permohonan}', [AdminPermohonanLayananController::class, 'show'])->name('permohonan.show');
        Route::patch('permohonan/{permohonan}', [AdminPermohonanLayananController::class, 'update'])->name('permohonan.update');
        Route::patch('permohonan/{permohonan}/terima', [AdminPermohonanLayananController::class, 'terima'])->name('permohonan.terima');
        Route::post('permohonan/{permohonan}/tolak', [AdminPermohonanLayananController::class, 'tolak'])->name('permohonan.tolak');
        Route::patch('permohonan/{permohonan}/lanjutkan', [AdminPermohonanLayananController::class, 'lanjutkan'])->name('permohonan.lanjutkan');
        Route::patch('permohonan/{permohonan}/proses-lanjut-surat', [AdminPermohonanLayananController::class, 'prosesLanjutSurat'])->name('permohonan.proses-lanjut-surat');
        Route::post('permohonan/{permohonan}/kirim-ke-kepala-kelurahan', [AdminPermohonanLayananController::class, 'kirimKeKepalaKelurahan'])->name('permohonan.kirim-ke-kepala-kelurahan');
        Route::post('permohonan/{permohonan}/kirim-wa-surat', [AdminPermohonanLayananController::class, 'kirimSuratKeWhatsappWarga'])->name('permohonan.kirim-wa-surat');
        Route::post('permohonan/{permohonan}/minta-revisi', [AdminPermohonanLayananController::class, 'mintaRevisi'])->name('permohonan.minta-revisi');

        Route::get('permohonan/{permohonan}/susun-surat', [SuratPermohonanController::class, 'susunSurat'])->name('permohonan.susun-surat');
        Route::post('permohonan/{permohonan}/susun-surat', [SuratPermohonanController::class, 'storeSusunSurat'])->name('permohonan.susun-surat.store');
        Route::post('permohonan/{permohonan}/terbitkan-surat', [SuratPermohonanController::class, 'terbitkan'])->name('permohonan.terbitkan-surat');
        Route::post('permohonan/{permohonan}/kirim-ke-kepala-kelurahan-ttd', [SuratPermohonanController::class, 'kirimKeKepalaKelurahanUntukTtd'])->name('permohonan.kirim-ke-kepala-kelurahan-ttd');
        Route::get('permohonan/{permohonan}/surat/preview', [SuratPermohonanController::class, 'preview'])->name('permohonan.surat.preview');
        Route::get('permohonan/{permohonan}/surat/unduh', [SuratPermohonanController::class, 'unduh'])->name('permohonan.surat.unduh');
        Route::get('permohonan/{permohonan}/surat/tampil', [SuratPermohonanController::class, 'tampil'])->name('permohonan.surat.tampil');

        Route::get('pengaturan-akun', [PengaturanAkunController::class, 'edit'])->name('pengaturan-akun.edit');
        Route::put('pengaturan-akun', [PengaturanAkunController::class, 'update'])->name('pengaturan-akun.update');

        Route::get('pesan', [AdminPesanKontakController::class, 'index'])->name('pesan.index');
        Route::get('pesan/{pesan}', [AdminPesanKontakController::class, 'show'])->name('pesan.show');
        Route::patch('pesan/{pesan}/belum-dibaca', [AdminPesanKontakController::class, 'markUnread'])->name('pesan.mark-unread');
    });
});
