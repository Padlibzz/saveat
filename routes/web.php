<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\MerchantDashboardController;
use App\Http\Controllers\MerchantListingController;
use Illuminate\Support\Facades\Route;

// ===== PUBLIK (tanpa login) =====
Route::get('/', [LandingController::class, 'index']);
Route::get('/listing-makanan', [ListingController::class, 'index'])->name('listing-makanan');
Route::get('/listing/{id}', [ListingController::class, 'show'])->name('listing.show');

// ===== GUEST (hanya bisa diakses sebelum login) =====
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', fn() => view('auth.login'))->name('login');
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/auth/register', fn() => view('auth.register'));
    Route::post('/auth/register', [AuthController::class, 'register']);

    Route::get('/auth/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');
    Route::post('/auth/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::get('/auth/reset-password/{token}', fn($token) => view('auth.reset-password', ['token' => $token]))->name('password.reset');
});

// ===== REDIRECT DASHBOARD berdasarkan role =====
Route::get('/dashboard', function () {
    return match (auth()->user()->peran) {
        'admin'    => redirect()->route('admin.dashboard'),
        'merchant' => redirect()->route('merchant.dashboard'),
        default    => redirect()->route('dashboard.konsumen'),
    };
})->middleware('auth')->name('dashboard');

// ===== AUTH (harus login) =====
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Merchant application — harus login, semua role bisa apply
    Route::get('/merchant-application', fn() => view('merchant-application'))->name('merchant.application');
    Route::post('/merchant-application', [ProfilController::class, 'applyMerchant'])->name('merchant.application.submit');

    // Checkout — harus login
    Route::get('/checkout/{id}', [ListingController::class, 'checkout'])->name('checkout');

    // Pesanan & riwayat
    Route::get('/pesanan', [ClaimController::class, 'pesananAktif'])->name('pesanan.aktif');
    Route::get('/pesanan/{id}', fn($id) => view('pesanan-detail', [
        'claim' => \App\Models\Claim::with('listing.merchant')->findOrFail($id)
    ]))->name('pesanan.detail');
    Route::get('/riwayat-pesanan', [ClaimController::class, 'riwayat'])->name('riwayat.pesanan');

    // Profile
    Route::get('/profile', [ProfilController::class, 'index'])->name('profile');

    // ===== KONSUMEN =====
    Route::middleware(['role:konsumen'])->group(function () {
        Route::get('/dashboard-konsumen', [RecommendationController::class, 'dashboard'])->name('dashboard.konsumen');
    });

    // ===== MERCHANT =====
    Route::middleware(['role:merchant'])->prefix('merchant')->group(function () {
        Route::get('/dashboard', [MerchantDashboardController::class, 'index'])->name('merchant.dashboard');
        Route::get('/upload-makanan', [MerchantListingController::class, 'create'])->name('merchant.upload');
        Route::post('/upload-makanan', [MerchantListingController::class, 'storeWeb'])->name('merchant.upload.submit');
        Route::get('/produk-aktif', [MerchantListingController::class, 'indexWeb'])->name('merchant.produk-aktif');
        Route::get('/produk-aktif/{id}/edit', [MerchantListingController::class, 'edit'])->name('merchant.listing.edit');
        Route::put('/produk-aktif/{id}', [MerchantListingController::class, 'updateWeb'])->name('merchant.listing.update');
        Route::delete('/produk-aktif/{id}', [MerchantListingController::class, 'destroy'])->name('merchant.listing.destroy');
        Route::get('/klaim-masuk', [MerchantDashboardController::class, 'klaimMasukWeb'])->name('merchant.klaim-masuk');
        Route::get('/scan-qr', [ClaimController::class, 'scanForm'])->name('merchant.scan-qr');
        Route::post('/scan-qr', [ClaimController::class, 'verifikasiWeb'])->name('merchant.scan-qr.submit');
    });

    // ===== ADMIN =====
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/profile', [ProfilController::class, 'index'])->name('admin.profile');
        Route::get('/analisis-penjualan', [AdminController::class, 'analisisPenjualan'])->name('admin.analisis');
        Route::get('/user-management', [AdminController::class, 'daftarUser'])->name('admin.users');
        Route::patch('/users/{id}/status', [AdminController::class, 'ubahStatusUser'])->name('admin.users.status');
        Route::get('/verifikasi-merchant', [AdminController::class, 'merchantMenunggu'])->name('admin.merchant-menunggu');
        Route::get('/verifikasi-merchant/{id}', [AdminController::class, 'detailMerchant'])->name('admin.merchant-detail');
        Route::patch('/verifikasi-merchant/{id}', [ProfilController::class, 'verifikasiMerchant'])->name('admin.merchant-detail.verifikasi');
        Route::patch('/listings/{id}/moderasi', [AdminController::class, 'moderasiListing'])->name('admin.listing.moderasi');
    });
});