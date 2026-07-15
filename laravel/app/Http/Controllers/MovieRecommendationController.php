<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieRecommendationController extends Controller
{
    public function index(Request $request)
    {
        $recommendations = null;
        $searchedMovie = null;
        $error = null;

        // Jika user melakukan pencarian
        if ($request->has('movie')) {
            $movieTitle = $request->input('movie');
            
            try {
                // Laravel "menembak" API Python kamu
                $response = Http::get("http://127.0.0.1:5000/api/recommend", [
                    'movie' => $movieTitle
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $recommendations = $data['recommendations'];
                    $searchedMovie = $data['searched_movie'];
                } else {
                    $error = "Film tidak ditemukan di database.";
                }
            } catch (\Exception $e) {
                $error = "Gagal terhubung ke server AI Python. Pastikan python app.py sudah jalan!";
            }
        }

        return view('recommendation', compact('recommendations', 'searchedMovie', 'error'));
    }

        public function landing()
    {
        return view('welcome');
    }
}