<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\MerchantListingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AbuseReportController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/listings', [ListingController::class, 'index']);

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

    Route::get('/activity-logs', [ActivityLogController::class, 'index']);
    
    Route::post('/abuse-reports', [AbuseReportController::class, 'store']); 
    Route::get('/abuse-reports', [AbuseReportController::class, 'index']); 
    Route::patch('/abuse-reports/{id}/status', [AbuseReportController::class, 'updateStatus']); 

    Route::get('/claims', [ClaimController::class, 'index']); 
    Route::post('/claims', [ClaimController::class, 'store']); 
    Route::patch('/claims/{id}/bayar', [ClaimController::class, 'bayar']); 
});

Route::middleware(['auth:sanctum', 'role:merchant'])->prefix('merchant')->group(function () {
    Route::get('/listings', [MerchantListingController::class, 'index']);
    Route::post('/listings', [MerchantListingController::class, 'store']);
    Route::patch('/listings/{id}', [MerchantListingController::class, 'update']);
    Route::patch('/listings/{id}/tutup', [MerchantListingController::class, 'tutup']);
    Route::post('/scan-qr', [ClaimController::class, 'scanQr']);

    Route::get('/dashboard/statistik', [MerchantDashboardController::class, 'statistik']);
    Route::get('/dashboard/klaim-masuk', [MerchantDashboardController::class, 'klaimMasuk']);

});