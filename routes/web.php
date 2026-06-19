<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfilController;

Route::get('/', function () {
    return view('landing');
});
Route::get('/auth/login', function () {
    return view('auth.login');
});
Route::get('/auth/register', function () {
    return view('auth.register');
});
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/dashboard', function () {
    return view('dashboard-user');
})->middleware('auth');
Route::get('/merchant/dashboard', function () {
    return view('dashboard-merchant');
})->middleware('auth');

Route::get('/admin/dashboard', function () {
    return view('dashboard-admin');
})->middleware('auth');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/auth/login');
})->name('logout');

Route::get('/merchant-application', function () {
    return view('merchant-application');
})->middleware('auth')->name('merchant.application');

Route::post('/merchant-application', [ProfilController::class, 'applyMerchant'])->middleware('auth') ->name('merchant.application.submit');

Route::get('/profile', function () {
    return view('profile');
})->name('profile');