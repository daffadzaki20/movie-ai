<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MovieController;

// Jalur URL: localhost:8000/api/movies
Route::get('/movies', [MovieController::class, 'index']);