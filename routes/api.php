<?php

use App\Http\Controllers\AbuseReportController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\MerchantListingController;
use App\Http\Controllers\NotificationController; // <-- TAMBAHAN: Import Controller Log
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

    // <-- TAMBAHAN: Route untuk melihat riwayat aktivitas
    Route::get('/activity-logs', [ActivityLogController::class, 'index']);
    // Endpoint Pelaporan Penyalahgunaan (Abuse Report)
    Route::post('/abuse-reports', [AbuseReportController::class, 'store']); // Konsumen lapor
    Route::get('/abuse-reports', [AbuseReportController::class, 'index']); // Admin lihat laporan
    Route::patch('/abuse-reports/{id}/status', [AbuseReportController::class, 'updateStatus']); // Admin ubah status

    // Transaksi & Klaim
    Route::get('/claims', [ClaimController::class, 'index']); // <-- (Tambahkan jika sebelumnya belum ada rute GET)
    Route::post('/claims', [ClaimController::class, 'store']); // <-- (Tambahkan jika sebelumnya belum ada rute POST)
    Route::patch('/claims/{id}/bayar', [ClaimController::class, 'bayar']); // <-- Rute bayar
});

// Endpoint untuk merchant disatukan secara eksklusif di dalam middleware 'role:merchant'
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:merchant')->prefix('merchant')->group(function () {
        Route::get('/listings', [MerchantListingController::class, 'index']);
        Route::post('/listings', [MerchantListingController::class, 'store']);
        Route::post('/scan-qr', [ClaimController::class, 'scanQr']);
    });
});
