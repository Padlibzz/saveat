<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\MerchantListingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfilController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/notification', [NotificationController::class, 'index']);
    Route::patch('/notification', [NotificationController::class, 'read']);

    Route::patch('/claims/{id}/selesai', [ClaimController::class, 'selesai']);

    Route::get('/profil', [ProfilController::class, 'index']);
    Route::post('/profil', [ProfilController::class, 'store']);
    Route::get('/profil/{id}', [ProfilController::class, 'show']);
    Route::patch('/profil/{id}', [ProfilController::class, 'update']);
    Route::patch('/profil/{id}/verifikasi', [ProfilController::class, 'verifikasiMerchant']);

    Route::get('/merchant/listings', [MerchantListingController::class, 'index']);
    Route::post('/merchant/listings', [MerchantListingController::class, 'store']);

});

Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:merchant')->prefix('merchant')->group(function () {
        Route::get('/listings', [MerchantListingController::class, 'index']);
        Route::post('/listings', [MerchantListingController::class, 'store']);
    });
});