<?php

use App\Http\Controllers\Api\ApiDataCPBController;
use App\Http\Controllers\Api\ApiDataVerifikasiCPBController;
use App\Http\Controllers\Api\ApiProfileController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\Api\ApiHomeController;
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
    Route::get('/home', [ApiHomeController::class, 'index']);
    Route::post('/profile', [ApiProfileController::class, 'getProfile']);
    Route::post('/profile-update', [ApiProfileController::class, 'updateProfile']);
    Route::resource('/dataCPB', ApiDataCPBController::class);
    Route::put('/updateVerifCPB/{id}', [ApiDataVerifikasiCPBController::class, 'updateVerifCPB']);
    Route::post('/verifikasiCPB', [ApiDataVerifikasiCPBController::class, 'addVerifikasiCPB']);
    // Route::match(['POST', 'PUT'], '/updateVerifCPB/{id}', [ApiDataVerifikasiCPBController::class, 'updateVerifCPB']);
    Route::get('/getVerifCPB', [ApiDataVerifikasiCPBController::class, 'getVerifCPB']);
    Route::delete('/delete/verifcpb/by-cpb/{id}', [ApiDataVerifikasiCPBController::class, 'destroyByCpbId']);
});
