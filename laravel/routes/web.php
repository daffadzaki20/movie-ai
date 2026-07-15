<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieRecommendationController;
use App\Http\Controllers\AuthController;

// 1. Halaman Publik (Landing Page)
Route::get('/', function () { return view('welcome'); });

// 2. Route Auth (Login/Register)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

// 3. Halaman Khusus Member (Wajib Login)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); });
    Route::get('/recommend', [MovieRecommendationController::class, 'index']);
});