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


Route::middleware(['auth'])->group(function () {
    // Dashboard untuk menampilkan daftar film terbaru
    Route::get('/dashboard', [MovieRecommendationController::class, 'dashboard']);
    
    // Halaman khusus untuk hasil rekomendasi AI
    Route::get('/recommend', [MovieRecommendationController::class, 'index'])->name('recommend');

    // My List
    Route::get('/my-list', [App\Http\Controllers\MyListController::class, 'index'])->name('my-list.index');
    Route::post('/my-list', [App\Http\Controllers\MyListController::class, 'store'])->name('my-list.store');

    // Genre
    Route::get('/genre', [App\Http\Controllers\GenreController::class, 'index'])->name('genre.index');
    Route::get('/genre/{name}', [App\Http\Controllers\GenreController::class, 'show'])->name('genre.show');

    // Profile (Update only, view is a modal)
    Route::post('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/movie/{movie_id}', [MovieRecommendationController::class, 'show'])->middleware('auth');
Route::get('/movie/detail/{id}', [MovieRecommendationController::class, 'detail'])->middleware('auth');