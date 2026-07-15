<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    public function index(): JsonResponse
    {
        // Mengambil semua data film dari database
        $movies = Movie::all();

        // Mengembalikan data dalam format JSON yang bersih untuk Python
        return response()->json([
            'success' => true,
            'message' => 'List data film untuk AI',
            'data'    => $movies
        ], 200);
    }
}