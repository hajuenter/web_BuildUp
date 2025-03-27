<?php

use App\Http\Controllers\Api\ApiDataCPBController;
use App\Http\Controllers\Api\ApiDataVerifikasiCPBController;
use App\Http\Controllers\Api\ApiProfileController;
use App\Http\Controllers\Api\ApiAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/status', function () {
    return response()->json(['status' => 'API connected'], 200);
});

//api auth
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/google-login', [ApiAuthController::class, 'googleLogin']);
Route::post('/send-otp', [ApiAuthController::class, 'sendOTP']);
Route::post('/verif-otp', [ApiAuthController::class, 'verifOTP']);
Route::post('/reset-password', [ApiAuthController::class, 'resetPassword']);

Route::middleware(['api.key'])->group(function () {
    Route::post('/profile', [ApiProfileController::class, 'getProfile']);
    Route::post('/profile-update', [ApiProfileController::class, 'updateProfile']);
    Route::resource('/dataCPB', ApiDataCPBController::class);
    Route::post('/verifikasiCPB', [ApiDataVerifikasiCPBController::class, 'addVerifikasiCPB']);
});
