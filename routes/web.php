<?php

use App\Http\Controllers\Admin\BeritaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Auth\LupaPasswordController;
use App\Http\Controllers\Petugas\InputCPBController;
use App\Http\Controllers\Petugas\PetugasProfileController;

//landing page
Route::get('/', function () {
    return view('buildup');
})->name('BuildUp');

//login
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
});

//logout
Route::controller(LogoutController::class)->group(function () {
    Route::post('/logout', 'logout')->name('logout');
});

//lupa password
Route::controller(LupaPasswordController::class)->group(function () {
    Route::get('/lupa-password', 'showLupaPasswordForm')->name('lupa.password');
    Route::post('/lupa-password-send', 'sendOTP')->name('kirim.otp');
    Route::get('/lupa-password-new', 'showKonfirPasswordForm')->name('konfir.password');
    Route::post('/lupa-password-update', 'updatePassword')->name('perbarui.password');
});

Route::middleware(['auth', 'checkRole'])->group(function () {
    // Admin Routes
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'showDashboardAdmin'])->name('admin.dashboard');

        // Profile
        Route::get('/profile', [ProfileController::class, 'showProfile'])->name('admin.profile');

        // Berita
        Route::get('/berita', [BeritaController::class, 'showBerita'])->name('admin.berita');
        Route::get('/berita/add', [BeritaController::class, 'showAddBerita'])->name('admin.add.berita');
        Route::post('/berita/store', [BeritaController::class, 'addBerita'])->name('admin.store.berita');
        Route::get('/berita/edit/{id}', [BeritaController::class, 'showEditBerita'])->name('admin.edit.berita');
        Route::post('/berita/update/{id}', [BeritaController::class, 'updateBerita'])->name('admin.update.berita');
        Route::delete('/berita/delete/{id}', [BeritaController::class, 'deleteBerita'])->name('admin.delete.berita');

        // Jadwal
        Route::get('/jadwal', [JadwalController::class, 'showJadwal'])->name('admin.jadwal');
    });

    // Petugas Routes
    Route::prefix('petugas')->group(function () {
        // CPB
        Route::get('/inputCPB', [InputCPBController::class, 'showFormInpuCPB'])->name('petugas.inputcpb');
        Route::post('/tambahCPB', [InputCPBController::class, 'inputCPB'])->name('petugas.create.inputcpb');
        Route::get('/cpb/edit/{id}', [InputCPBController::class, 'showEditCPB'])->name('petugas.edit.cpb');
        Route::post('/cpb/update/{id}', [InputCPBController::class, 'updateCPB'])->name('petugas.update.cpb');
        Route::delete('/cpb/delete/{id}', [InputCPBController::class, 'deleteCPB'])->name('petugas.delete.cpb');

        // Profile
        Route::get('/profile', [PetugasProfileController::class, 'showPetugasProfile'])->name('petugas.profile');
        Route::post('/profile/update', [PetugasProfileController::class, 'updateProfile'])->name('petugas.profile.update');
        Route::post('/ganti-password', [PetugasProfileController::class, 'changePassword'])->name('petugas.ganti.password');
    });
});
