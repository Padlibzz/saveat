<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RecommendationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index']);
Route::get('/listing-makanan', [ListingController::class, 'index'])->name('listing-makanan');

Route::middleware('guest')->group(function () {
    Route::get('/auth/login', function () { return view('auth.login'); })->name('login');
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/auth/register', function () { return view('auth.register'); });
    Route::post('/auth/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/auth/login');
    })->name('logout');

    Route::get('/dashboard', [RecommendationController::class, 'dashboard']); // Menggunakan controller rekomendasi
    Route::get('/profile', [ProfilController::class, 'index'])->name('profile');
    Route::get('/checkout/{id}', [ListingController::class, 'checkout'])->name('checkout');
    Route::get('/api/recommendations', [RecommendationController::class, 'index']);

    Route::get('/merchant-application', function () { return view('merchant-application'); })->name('merchant.application');
    Route::post('/merchant-application', [ProfilController::class, 'applyMerchant'])->name('merchant.application.submit');

    Route::get('/merchant/dashboard', function () {
        return view('dashboard-merchant');
    });

    Route::get('/admin/dashboard', [AdminController::class, 'index']);

    // Password reset routes (preserved from stashed changes)
    Route::get('/auth/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    Route::post('/auth/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    Route::get('/auth/reset-password/{token}', function ($token) {
        return view('auth.reset-password', ['token' => $token]);
    })->name('password.reset');
});
