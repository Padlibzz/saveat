<?php

use App\Http\Controllers\AbuseReportController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MerchantDashboardController;
use App\Http\Controllers\MerchantListingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\PublicStatsController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Route Publik (Tanpa Autentikasi)
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/listings', [ListingController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/payment-methods', [ClaimController::class, 'paymentMethods']);

Route::post('/payments/webhook', [PaymentController::class, 'webhook']);

Route::get('/stats', [PublicStatsController::class, 'index']);

// Route Terproteksi (Memerlukan Autentikasi Sanctum)
Route::middleware('auth:sanctum')->group(function () {

    // Auth & User
    Route::get('/user', fn (Request $r) => $r->user());
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Manajemen Profil
    Route::get('/profil', [ProfilController::class, 'index']);
    Route::post('/profil', [ProfilController::class, 'store']);
    Route::get('/profil/{id}', [ProfilController::class, 'show']);
    Route::patch('/profil/{id}', [ProfilController::class, 'update']);

    // Konsumen: Klaim / Pesanan
    Route::get('/claims', [ClaimController::class, 'index']);
    Route::post('/claims', [ClaimController::class, 'store']);
    Route::patch('/claims/{id}/selesai', [ClaimController::class, 'selesai']);

    // Konsumen: Pembayaran Midtrans (Grouped via Prefix)
    Route::prefix('payments')->group(function () {
        Route::get('/{claimId}/status', [PaymentController::class, 'checkStatus']);
    });

    // Notifikasi
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'readAll']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'read']);

    // Pelaporan Masalah
    Route::post('/abuse-reports', [AbuseReportController::class, 'store']);

    // Log Aktivitas
    Route::get('/activity-logs', [ActivityLogController::class, 'index']);

    // Izin Lokasi
    Route::patch('/profil/lokasi', [ProfilController::class, 'updateLokasi']);
    Route::get('/recommendations', [RecommendationController::class, 'index']);

    Route::post('/reviews', [ReviewController::class, 'store']);
});

// Route Khusus Merchant
Route::middleware(['auth:sanctum', 'role:merchant'])->prefix('merchant')->group(function () {
    Route::get('/listings', [MerchantListingController::class, 'index']);
    Route::post('/listings', [MerchantListingController::class, 'store']);
    Route::patch('/listings/{id}', [MerchantListingController::class, 'update']);
    Route::patch('/listings/{id}/tutup', [MerchantListingController::class, 'tutup']);
    Route::post('/scan-qr', [ClaimController::class, 'scanQr']);

    Route::get('/dashboard/statistik', [MerchantDashboardController::class, 'statistik']);
    Route::get('/dashboard/klaim-masuk', [MerchantDashboardController::class, 'klaimMasuk']);
});

// Route Khusus Admin
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/statistik', [AdminController::class, 'statistik']);
    Route::get('/merchants/menunggu', [AdminController::class, 'merchantMenunggu']);
    Route::patch('/profil/{id}/verifikasi', [ProfilController::class, 'verifikasiMerchant']);
    Route::patch('/listings/{id}/moderasi', [AdminController::class, 'moderasiListing']);
    Route::get('/users', [AdminController::class, 'daftarUser']);
    Route::patch('/users/{id}/status', [AdminController::class, 'ubahStatusUser']);
    Route::get('/abuse-reports', [AbuseReportController::class, 'index']);
    Route::patch('/abuse-reports/{id}/status', [AbuseReportController::class, 'updateStatus']);
});
