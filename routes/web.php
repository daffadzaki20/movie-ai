<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieRecommendationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MyListController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\MovieController as AdminMovieController;

// 1. Halaman Publik (Landing Page)
Route::get('/', function () {
    return view('welcome');
});

// 2. Route Auth (Login/Register)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 3. User Routes (Terproteksi Auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [MovieRecommendationController::class, 'dashboard']);
    Route::get('/recommend', [MovieRecommendationController::class, 'index'])->name('recommend');

    // My List
    Route::get('/my-list', [MyListController::class, 'index'])->name('my-list.index');
    Route::post('/my-list', [MyListController::class, 'store'])->name('my-list.store');

    // Genre
    Route::get('/genre', [GenreController::class, 'index'])->name('genre.index');
    Route::get('/genre/{name}', [GenreController::class, 'show'])->name('genre.show');

    // Profile
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// 4. Movie Detail Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/movie/{movie_id}', [MovieRecommendationController::class, 'show']);
    Route::get('/movie/detail/{id}', [MovieRecommendationController::class, 'detail']);
});

// 5. Admin Routes
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminMovieController::class, 'dashboard'])->name('dashboard');
    Route::resource('movies', AdminMovieController::class);
});