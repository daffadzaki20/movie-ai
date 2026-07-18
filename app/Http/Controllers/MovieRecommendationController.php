<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;
use Illuminate\Support\Facades\Log;

class MovieRecommendationController extends Controller
{
    // Helper untuk mengambil poster dari TMDB
    private function getMoviePoster($title)
    {
        try {
            $apiKey = env('TMDB_API_KEY');
            $response = Http::timeout(3)->get("https://api.themoviedb.org/3/search/movie", [
                'api_key' => $apiKey,
                'query' => $title
            ]);

            $data = $response->json();
            if (!empty($data['results'])) {
                $path = $data['results'][0]['poster_path'];
                return $path ? "https://image.tmdb.org/t/p/w500" . $path : null;
            }
        } catch (\Exception $e) {
            Log::error("TMDB Error: " . $e->getMessage());
        }
        return null;
    }

    public function dashboard()
    {
        $data = [
            'populer'  => \App\Models\Movie::latest()->limit(10)->get(),
            'action'   => \App\Models\Movie::inRandomOrder()->limit(10)->get(),
            'drama'    => \App\Models\Movie::inRandomOrder()->limit(10)->get(),
            'thriller' => \App\Models\Movie::inRandomOrder()->limit(10)->get(),
            'comedy'   => \App\Models\Movie::inRandomOrder()->limit(10)->get(),
        ];
        
        return view('dashboard', compact('data'));
    }

    public function index(Request $request)
    {
        $recommendations = [];
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
                    $titles = $data['recommendations'] ?? [];
                    $searchedMovie = $data['searched_movie'] ?? $movieTitle;

                    // Mengubah daftar judul menjadi array yang berisi judul + poster
                    foreach ($titles as $title) {
                        $recommendations[] = [
                            'title' => $title,
                            'poster' => $this->getMoviePoster($title)
                        ];
                    }
                } else {
                    $error = "Film tidak ditemukan atau API sedang bermasalah.";
                }
            } catch (\Exception $e) {
                Log::error("API Error: " . $e->getMessage());
                $error = "Gagal terhubung ke server AI Python.";
            }
        }

        return view('recommendation', compact('recommendations', 'searchedMovie', 'error'));
    }

    public function show($movie_id)
    {
        $movie = \App\Models\Movie::where('movie_id', $movie_id)->firstOrFail();
        return view('movie_detail', compact('movie'));
    }

    public function detail($id)
    {
        $movie = \App\Models\Movie::findOrFail($id);
        return view('movie_detail', compact('movie'));
    }
}