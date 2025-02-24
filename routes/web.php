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
use App\Http\Controllers\Petugas\ProfileController as PetugasProfileController;

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
    Route::post('/lupa-password', 'sendOTP')->name('kirim.otp');
    Route::get('/lupa-password-new', 'showKonfirPasswordForm')->name('konfir.password');
    Route::post('/lupa-password-new', 'updatePassword')->name('perbarui.password');
});

//admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'showDashboardAdmin'])->name('admin.dashboard');

    //profile
    Route::get('/admin/profile', [ProfileController::class, 'showProfile'])->name('admin.profile');

    //berita
    Route::get('/admin/berita', [BeritaController::class, 'showBerita'])->name('admin.berita');
    Route::get('/admin/berita/add', [BeritaController::class, 'showAddBerita'])->name('admin.add.berita');
    Route::post('/admin/berita/store', [BeritaController::class, 'addBerita'])->name('admin.store.berita');
    Route::get('/admin/berita/edit/{id}', [BeritaController::class, 'showEditBerita'])->name('admin.edit.berita');
    Route::post('/admin/berita/update/{id}', [BeritaController::class, 'updateBerita'])->name('admin.update.berita');
    Route::delete('/admin/berita/delete/{id}', [BeritaController::class, 'deleteBerita'])->name('admin.delete.berita');

    //jadwal
    Route::get('/admin/jadwal', [JadwalController::class, 'showJadwal'])->name('admin.jadwal');
});

//petugas
Route::middleware(['auth', 'petugas'])->group(function () {
    Route::get('/petugas/inputCPB', [InputCPBController::class, 'showFormInpuCPB'])->name('petugas.inputcpb');
    Route::post('/petugas/tambahCPB', [InputCPBController::class, 'inputCPB'])->name('petugas.create.inputcpb');
    Route::get('/petugas/profile', [PetugasProfileController::class, 'showProfile'])->name('petugas.profile');
});
