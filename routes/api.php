<?php

use App\Http\Controllers\Api\ApiDataCPBController;
use App\Http\Controllers\Api\ApiDataVerifikasiCPBController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/status', function () {
    return response()->json(['status' => 'API connected'], 200);
});

//api auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/google-login', [AuthController::class, 'googleLogin']);
Route::post('/send-otp', [AuthController::class, 'sendOTP']);
Route::post('/verif-otp', [AuthController::class, 'verifOTP']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware(['api.key'])->group(function () {
    Route::resource('/dataCPB', ApiDataCPBController::class);
    Route::resource('/dataVerifikasiCPB', ApiDataVerifikasiCPBController::class);
});
