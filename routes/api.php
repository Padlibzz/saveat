<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ClaimController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::get('/notification', [NotificationController::class, 'index']);
Route::patch('/notification', [NotificationController::class, 'read']);

Route::patch('/claims/{id}/selesai', [ClaimController::class, 'selesai']);