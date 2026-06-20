<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\RecommendationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index']);

Route::get('/auth/login', function () {
    return view('auth.login');
});
Route::get('/auth/register', function () {
    return view('auth.register');
});
Route::post('/auth/register', [AuthController::class, 'register']);

Route::post('/auth/login', [AuthController::class, 'login']);

Route::get('/dashboard-konsumen', function (\Illuminate\Http\Request $request, RecommendationController $recommendationController) {
    // Memanggil metode internal untuk mendapatkan listing
    // Menggunakan refleksi atau memanggil metode secara langsung jika memungkinkan, 
    // namun karena getRecommendations adalah private, kita gunakan dashboard saja
    return redirect('/dashboard');
})->middleware('auth');

use App\Http\Controllers\AdminController;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', [AdminController::class, 'daftarUser'])->name('admin.users');
    Route::get('/profile', [ProfilController::class, 'index'])->name('admin.profile');
    Route::get('/analisis-penjualan', [AdminController::class, 'analisisPenjualan'])->name('admin.analisis');
    Route::get('/merchants/menunggu', [AdminController::class, 'merchantMenunggu'])->name('admin.merchant-menunggu');
    Route::get('/merchants/{id}', [AdminController::class, 'detailMerchant'])->name('admin.merchant-detail');
    Route::patch('/merchants/{id}/verifikasi', [ProfilController::class, 'verifikasiMerchant'])->name('admin.merchant-verifikasi');
});

Route::get('/dashboard-admin', [AdminController::class, 'statistik'])->name('admin.dashboard')->middleware('auth', 'role:admin');

Route::get('/dashboard-merchant', function () {
    return view('dashboard-merchant');
})->middleware('auth', 'role:merchant');

Route::get('/merchant/apply', function () {
    return view('merchant-application');
})->name('merchant.application')->middleware('auth');

Route::post('/merchant/apply', [ProfilController::class, 'applyMerchant'])->name('merchant.application.submit')->middleware('auth');

Route::get('/profile', [ProfilController::class, 'index'])
    ->name('profile')
    ->middleware('auth');

Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/auth/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::post('/auth/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');

Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/auth/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::get('/api/recommendations', [RecommendationController::class, 'index']);

Route::get('/dashboard', [RecommendationController::class, 'dashboard'])
    ->middleware('auth');
