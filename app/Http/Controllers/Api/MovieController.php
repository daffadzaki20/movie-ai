<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    public function index(): JsonResponse
    {
        // Pastikan model Movie Anda memang memiliki kolom 'title' dan 'overview'
        $movies = Movie::all(['id', 'title', 'overview']);

        return response()->json([
            'success' => true,
            'data'    => $movies    
        ], 200);
    }
}