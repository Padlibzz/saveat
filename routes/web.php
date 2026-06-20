<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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
    return view('dashboard');
})->middleware('auth');

Route::get('dashboard-admin', function () {
    return view('dashboard-admin');
})->middleware('auth');
