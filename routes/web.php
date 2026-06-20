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

Route::get('/dashboard-admin', function () {
    return view('dashboard-admin');
})->middleware('auth', 'role:admin');

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

Route::get('/api/recommendations', [RecommendationController::class, 'index']);

Route::get('/dashboard', [RecommendationController::class, 'dashboard'])
    ->middleware('auth');
