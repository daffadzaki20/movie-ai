<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;
use Illuminate\Support\Facades\Log;

class MovieRecommendationController extends Controller
{
    // Fungsi untuk /dashboard
    public function dashboard()
    {
        // Mengambil data film dengan kategori berbeda
        $data = [
            'populer'  => \App\Models\Movie::latest()->limit(10)->get(),
            'action'   => \App\Models\Movie::inRandomOrder()->limit(10)->get(),
            'drama'    => \App\Models\Movie::inRandomOrder()->limit(10)->get(),
            'thriller' => \App\Models\Movie::inRandomOrder()->limit(10)->get(),
            'comedy'   => \App\Models\Movie::inRandomOrder()->limit(10)->get(),
        ];
        
        // Mengirim variabel $data ke view
        return view('dashboard', compact('data'));
    }
    // Fungsi untuk /recommend
    public function index(Request $request)
    {
        $recommendations = null;
        $searchedMovie = null;
        $error = null;

        if ($request->has('movie') && !empty($request->input('movie'))) {
            $movieTitle = $request->input('movie');
            
            try {
                $response = Http::timeout(5)->get("http://127.0.0.1:5000/api/recommend", [
                    'movie' => $movieTitle
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $recommendations = $data['recommendations'] ?? [];
                    $searchedMovie = $data['searched_movie'] ?? $movieTitle;
                } else {
                    $error = "Film tidak ditemukan atau API sedang bermasalah.";
                }
            } catch (\Exception $e) {
                Log::error("API Error: " . $e->getMessage());
                $error = "Gagal terhubung ke server AI Python.";
            }
        }

        // Mengirimkan hasil ke view 'recommendation' (sesuaikan nama filenya)
        return view('recommendation', compact('recommendations', 'searchedMovie', 'error'));
    }

    public function show($movie_id)
    {
        // Ambil data film dari database berdasarkan movie_id
        $movie = \App\Models\Movie::where('movie_id', $movie_id)->firstOrFail();
        
        return view('movie_detail', compact('movie'));
    }

        public function detail($id)
    {
        $movie = \App\Models\Movie::findOrFail($id);
        return view('movie_detail', compact('movie'));
    }
}