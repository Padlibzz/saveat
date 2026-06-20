<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
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

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [RecommendationController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard-konsumen', function () {
        return redirect('/dashboard');
    });

    Route::get('/profile', [ProfilController::class, 'index'])->name('profile');
    Route::get('/checkout/{id}', [ListingController::class, 'checkout'])->name('checkout');
    Route::get('/api/recommendations', [RecommendationController::class, 'index']);

    // Merchant Routes
    Route::get('/merchant-application', function () { return view('merchant-application'); })->name('merchant.application');
    Route::post('/merchant-application', [ProfilController::class, 'applyMerchant'])->name('merchant.application.submit');

    Route::middleware(['role:merchant'])->group(function () {
        Route::get('/dashboard-merchant', function () {
            return view('dashboard-merchant');
        });
    });

    // Admin Routes
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'statistik'])->name('admin.dashboard');
        Route::get('/users', [AdminController::class, 'daftarUser'])->name('admin.users');
        Route::get('/user-management', [AdminController::class, 'daftarUser'])->name('admin.user-management'); // Added for backward compatibility
        Route::get('/profile', [ProfilController::class, 'index'])->name('admin.profile');
        Route::get('/analisis-penjualan', [AdminController::class, 'analisisPenjualan'])->name('admin.analisis');
        Route::get('/merchants/menunggu', [AdminController::class, 'merchantMenunggu'])->name('admin.merchant-menunggu');
        Route::get('/merchants/{id}', [AdminController::class, 'detailMerchant'])->name('admin.merchant-detail');
        // Route::patch('/merchants/{id}/verifikasi', [ProfilController::class, 'verifikasiMerchant'])->name('admin.merchant-verifikasi');
    });
    
    // Added for backward compatibility with view expectations
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/dashboard-admin', [AdminController::class, 'index']);
    });
});
