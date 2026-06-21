<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RecommendationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [LandingController::class, 'index']);
Route::get('/listing-makanan', [ListingController::class, 'index'])->name('listing-makanan');

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', function () { return view('auth.login'); })->name('login');
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/auth/register', function () { return view('auth.register'); });
    Route::post('/auth/register', [AuthController::class, 'register']);
    
    // Password reset routes
    Route::get('/auth/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    Route::post('/auth/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::get('/auth/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
});

// Centralized Dashboard Redirection
Route::get('/dashboard', function () {
    $user = auth()->user();
    return match ($user->peran) {
        'admin' => redirect()->route('admin.dashboard'),
        'merchant' => redirect('/dashboard-merchant'),
        default => redirect()->route('dashboard.konsumen'),
    };
})->middleware('auth')->name('dashboard');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Konsumen Routes
    Route::middleware(['role:konsumen'])->group(function () {
        Route::get('/dashboard-konsumen', [RecommendationController::class, 'dashboard'])->name('dashboard.konsumen');
    });

    // Merchant Application Routes
    Route::get('/merchant-application', function () { return view('merchant-application'); })->name('merchant.application');
    Route::post('/merchant-application', [ProfilController::class, 'applyMerchant'])->name('merchant.application.submit');

    // Merchant Routes
    Route::middleware(['role:merchant'])->group(function () {
        Route::get('/dashboard-merchant', function () {
            return view('dashboard-merchant');
        })->name('dashboard.merchant');
    });

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'statistik'])->name('admin.dashboard');
        Route::get('/profile', [ProfilController::class, 'index'])->name('admin.profile');
        Route::get('/analisis-penjualan', [AdminController::class, 'analisisPenjualan'])->name('admin.analisis');
        Route::get('/users', [AdminController::class, 'daftarUser'])->name('admin.users');
        Route::get('/merchants/menunggu', [AdminController::class, 'merchantMenunggu'])->name('admin.merchant-menunggu');
    });
    
    // Transaction Routes
    Route::post('/proses-transaksi', [ClaimController::class, 'prosesTransaksi'])->name('transaksi.proses');
    Route::post('/konfirmasi-pembayaran/{id}', [ClaimController::class, 'konfirmasiPembayaran'])->name('transaksi.konfirmasi');
    Route::get('/pesanan/{id}', function ($id) {
        $claim = \App\Models\Claim::with('listing.merchant')->findOrFail($id);
        return view('pesanan-detail', compact('claim'));
    })->name('pesanan.detail');
    
    // Shared Routes
    Route::get('/profile', [ProfilController::class, 'index'])->name('profile');
    Route::get('/checkout/{id}', [ListingController::class, 'checkout'])->name('checkout');
});
