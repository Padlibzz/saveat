<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AbuseReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\MerchantDashboardController;
use App\Http\Controllers\MerchantListingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\CategoryController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/listings', [ListingController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/payment-methods', [ClaimController::class, 'paymentMethods']);
Route::post('/payments/webhook', [PaymentController::class, 'webhook']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $r) => $r->user());
    Route::post('/refresh-token', [AuthController::class, 'refreshToken']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profil', [ProfilController::class, 'index']);
    Route::post('/profil', [ProfilController::class, 'store']);
    Route::get('/profil/{id}', [ProfilController::class, 'show']);
    Route::patch('/profil/{id}', [ProfilController::class, 'update']);

    Route::get('/claims', [ClaimController::class, 'index']); 
    Route::post('/claims', [ClaimController::class, 'store']); 
    Route::post('/claims/{id}/bayar', [ClaimController::class, 'bayar']);
    Route::patch('/claims/{id}/selesai', [ClaimController::class, 'selesai']); 

    Route::post('/payments/{claimId}/create', [PaymentController::class, 'createTransaction']);
    Route::get('/payments/{claimId}/status', [PaymentController::class, 'checkStatus']);
   

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::patch('/notifications/read-all', [NotificationController::class, 'readAll']);
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'read']);
    
    Route::post('/abuse-reports', [AbuseReportController::class, 'store']);

    Route::get('/activity-logs', [ActivityLogController::class, 'index']);   
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