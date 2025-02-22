<?php

use App\Http\Controllers\Api\ApiDataCPBController;
use App\Http\Controllers\Api\ApiDataVerifikasiCPBController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['api.key'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('auth:sanctum');

    // API Data CPB
    Route::resource('/dataCPB', ApiDataCPBController::class);

    // API Data Verifikasi CPB
    Route::resource('/dataVerifikasiCPB', ApiDataVerifikasiCPBController::class);
});

//api auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
