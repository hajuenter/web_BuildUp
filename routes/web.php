<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DataController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RekapanCPBController;
use App\Http\Controllers\Petugas\InputCPBController;
use App\Http\Controllers\Auth\LupaPasswordController;
use App\Http\Controllers\Admin\ImageVerifikasiController;
use App\Http\Controllers\Petugas\GantiPasswordController;
use App\Http\Controllers\Petugas\PetugasProfileController;
use App\Http\Controllers\Admin\RekapanVerifikasiController;
use App\Http\Controllers\Admin\AdminGantiPasswordController;

// Landing Page
Route::get('/', [LandingPageController::class, 'index'])->name('BuildUp');

// Register
Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegisterForm')->name('register');
    Route::post('/register', 'register');
});

// Login
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
});

// Logout
Route::controller(LogoutController::class)->group(function () {
    Route::post('/logout', 'logout')->name('logout');
});

// Lupa Password
Route::controller(LupaPasswordController::class)->group(function () {
    Route::get('/lupa-password', 'showLupaPasswordForm')->name('lupa.password');
    Route::post('/lupa-password-send', 'sendOTP')->name('kirim.otp');
    Route::get('/lupa-password-new', 'showKonfirPasswordForm')->name('konfir.password');
    Route::post('/lupa-password-update', 'updatePassword')->name('perbarui.password');
});

// Kirim Pesan
Route::post('/kirim-pesan', [LandingPageController::class, 'kirim'])->name('kirim.pesan');

Route::middleware(['auth', 'checkRole'])->group(function () {
    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'showDashboardAdmin'])->name('admin.dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'showProfile'])->name('admin.profile');
        Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('admin.profile.update');
        Route::get('/ganti-password', [AdminGantiPasswordController::class, 'showChangePassword'])->name('admin.ganti.password');
        Route::post('/ganti-password-new', [AdminGantiPasswordController::class, 'ChangePassword'])->name('admin.update.password');

        // Berita
        Route::get('/berita', [BeritaController::class, 'showBerita'])->name('admin.berita');
        Route::get('/berita/add', [BeritaController::class, 'showAddBerita'])->name('admin.add.berita');
        Route::post('/berita/store', [BeritaController::class, 'addBerita'])->name('admin.store.berita');
        Route::get('/berita/edit/{id}', [BeritaController::class, 'showEditBerita'])->name('admin.edit.berita');
        Route::put('/berita/update/{id}', [BeritaController::class, 'updateBerita'])->name('admin.update.berita');
        Route::delete('/berita/delete/{id}', [BeritaController::class, 'deleteBerita'])->name('admin.delete.berita');

        // Jadwal
        Route::get('/jadwal', [JadwalController::class, 'showJadwal'])->name('admin.jadwal');
        Route::get('/jadwal/add', [JadwalController::class, 'showAddJadwal'])->name('admin.add.jadwal');
        Route::post('/jadwal/store', [JadwalController::class, 'AddJadwal'])->name('admin.store.jadwal');
        Route::get('/jadwal/edit/{id}', [JadwalController::class, 'showEditJadwal'])->name('admin.edit.jadwal');
        Route::put('/jadwal/update/{id}', [JadwalController::class, 'updateJadwal'])->name('admin.update.jadwal');
        Route::delete('/jadwal/delete/{id}', [JadwalController::class, 'deleteJadwal'])->name('admin.delete.jadwal');

        // Data CPB
        Route::get('/data/cpb', [DataController::class, 'showDataCPB'])->name('admin.data_cpb');
        Route::get('/data/cpb/edit/{id}', [DataController::class, 'showEditDataCPB'])->name('admin.edit.data_cpb');
        Route::put('/data/cpb/update/{id}', [DataController::class, 'updateDataCPB'])->name('admin.update.data_cpb');
        Route::delete('/data/cpb/{id}', [DataController::class, 'deleteDataCPB'])->name('admin.delete.data_cpb');

        // Data Verif
        Route::get('/data/verif/cpb', [DataController::class, 'showDataVerifCPB'])->name('admin.data_verif_cpb');
        Route::get('/data/verif/cpb/{id}', [DataController::class, 'showEditDataVerifCPB'])->name('admin.edit.data_verif_cpb');
        Route::put('/data/verif/cpb/update/{id}', [DataController::class, 'updateDataVerifCPB'])->name('admin.update.data_verif_cpb');
        Route::delete('/data/verif/cpb', [DataController::class, 'deleteDataVerifCPB'])->name('admin.delete.data_verif_cpb');

        // Data User
        Route::get('/data/role', [DataController::class, 'showDataRole'])->name('admin.data_role');
        Route::post('/user/verify/{id}', [DataController::class, 'verifyUser'])->name('admin.user.verify');
        Route::post('/user/unverify/{id}', [DataController::class, 'unverifyUser'])->name('admin.user.unverify');
        Route::delete('/user/delete/{id}', [DataController::class, 'deleteUser'])->name('admin.user.delete');
        Route::get('/data/pengguna/add', [DataController::class, 'showPetugasAdd'])->name('admin.user.petugas.add');
        Route::post('/data/pengguna/create', [DataController::class, 'createPengguna'])->name('admin.user.create');
        Route::put('/data/token', [DataController::class, 'updateToken'])->name('admin.token.update');

        // Rekapan CPB
        Route::get('/rekapan/cpb', [RekapanCPBController::class, 'showRekapCPB'])->name('admin.rekap.cpb');
        Route::post('/rekapan/cpb/pdf', [RekapanCPBController::class, 'downloadCpbPdf'])->name('rekap.cpb.pdf');
        Route::post('/rekapan/cpb/excel', [RekapanCPBController::class, 'downloadCpbExcel'])->name('rekap.cpb.excel');
        Route::post('/rekapan/cpb/word', [RekapanCPBController::class, 'downloadCpbWord'])->name('rekap.cpb.word');

        // Rekapan Verifikasi CPB
        Route::get('/rekapan/verifikasi', [RekapanVerifikasiController::class, 'showRekapVerif'])->name('admin.rekap.verif');
        Route::post('/rekapan/verifikasi/pdf', [RekapanVerifikasiController::class, 'downloadVerifikasiPdf'])->name('rekap.verif.pdf');
        Route::post('/rekapan/verifikasi/excel', [RekapanVerifikasiController::class, 'downloadVerifikasiExcel'])->name('rekap.verif.excel');
        Route::post('/rekapan/verifikasi/word', [RekapanVerifikasiController::class, 'downloadVerifikasiWord'])->name('rekap.verif.word');

        // Image Verif
        Route::get('/verifikasi/image', [ImageVerifikasiController::class, 'showImageVerif'])->name('admin.verif.image');
        Route::post('/admin/download-folder', [ImageVerifikasiController::class, 'downloadFolder'])
            ->name('admin.download.folder');
    });

    // Petugas Routes
    Route::prefix('petugas')->group(function () {
        // CPB input
        Route::get('/inputCPB', [InputCPBController::class, 'showFormInputCPB'])->name('petugas.inputcpb');
        Route::get('/dataCPB', [InputCPBController::class, 'showDataCPB'])->name('petugas.datacpb');
        Route::get('/cpb/cetak-surat/{id}', [InputCPBController::class, 'cetakSurat'])->name('cpb.cetakSurat');
        Route::post('/tambahCPB', [InputCPBController::class, 'inputCPB'])->name('petugas.create.inputcpb');
        Route::get('/cpb/edit/{id}', [InputCPBController::class, 'showEditCPB'])->name('petugas.edit.cpb');
        Route::put('/cpb/update/{id}', [InputCPBController::class, 'updateCPB'])->name('petugas.update.cpb');
        Route::delete('/cpb/delete/{id}', [InputCPBController::class, 'deleteCPB'])->name('petugas.delete.cpb');

        // Profile
        Route::get('/profile', [PetugasProfileController::class, 'showPetugasProfile'])->name('petugas.profile');
        Route::put('/profile/update', [PetugasProfileController::class, 'updateProfile'])->name('petugas.profile.update');
        Route::get('/ganti-password', [GantiPasswordController::class, 'showChangePassword'])->name('petugas.ganti.password');
        Route::post('/ganti-password-new', [GantiPasswordController::class, 'ChangePassword'])->name('petugas.update.password');
    });
});
