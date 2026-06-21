<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\MerchantDashboardController;
use App\Http\Controllers\MerchantListingController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ================= HALAMAN PUBLIK =================
Route::get('/', [LandingController::class, 'index']);
Route::get('/listing-makanan', [ListingController::class, 'index'])->name('listing-makanan');

// ================= GUEST MIDDLEWARE (Belum Login) =================
Route::middleware('guest')->group(function () {
    Route::get('/auth/login', function () { return view('auth.login'); })->name('login');
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::get('/auth/register', function () { return view('auth.register'); });
    Route::post('/auth/register', [AuthController::class, 'register']);
});

// ================= AUTH MIDDLEWARE (Sudah Login) =================
Route::middleware('auth')->group(function () {
    
    // Proses Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/auth/login');
    })->name('logout');

    // ================= AREA KONSUMEN =================
    Route::get('/dashboard', [RecommendationController::class, 'dashboard'])->name('dashboard'); 
    Route::get('/profile', function () { return view('profile'); })->name('profile');
    Route::get('/checkout/{id}', [ListingController::class, 'checkout'])->name('checkout');
    Route::get('/api/recommendations', [RecommendationController::class, 'index']);

    // Pendaftaran Merchant
    Route::get('/merchant-application', function () { return view('merchant-application'); })->name('merchant.application');
    Route::post('/merchant-application', [ProfilController::class, 'applyMerchant'])->name('merchant.application.submit');

    // ================= AREA MERCHANT =================
    Route::get('/merchant/dashboard', [MerchantDashboardController::class, 'index'])->name('merchant.dashboard');
    Route::get('/merchant/produk-aktif', [MerchantListingController::class, 'index'])->name('merchant.produk-aktif');
    Route::get('/merchant/upload-makanan', function () { return view('upload-makanan'); })->name('merchant.upload-makanan');
    Route::post('/merchant/listing', [MerchantListingController::class, 'store'])->name('merchant.listing.store');
    Route::post('/merchant/listing/{id}/tutup', [MerchantListingController::class, 'tutup'])->name('merchant.listing.tutup');

    // ================= AREA ADMIN =================
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/verifikasi-merchant', [AdminController::class, 'halamanVerifikasi'])->name('admin.verifikasi.index');
    Route::post('/admin/merchant/{id}/setujui', [AdminController::class, 'setujuiMerchant'])->name('admin.merchant.setujui');
    Route::post('/admin/merchant/{id}/tolak', [AdminController::class, 'tolakMerchant'])->name('admin.merchant.tolak');
});