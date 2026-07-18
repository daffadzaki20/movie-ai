<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Movie;
use Illuminate\Support\Facades\Log;

class MovieRecommendationController extends Controller
{
    // Helper untuk mengambil poster dari TMDB dengan Cache
    private function getMoviePoster($title)
    {
        return cache()->remember('poster_' . md5($title), 86400, function () use ($title) {
            try {
                $apiKey = env('TMDB_API_KEY');
                $response = Http::timeout(2)->get("https://api.themoviedb.org/3/search/movie", [
                    'api_key' => $apiKey,
                    'query' => $title
                ]);

                $data = $response->json();
                if (!empty($data['results']) && isset($data['results'][0]['poster_path'])) {
                    return "https://image.tmdb.org/t/p/w500" . $data['results'][0]['poster_path'];
                }
            } catch (\Exception $e) {
                Log::error("TMDB Error: " . $e->getMessage());
            }
            return null;
        });
    }

    public function dashboard()
    {
        // Menggunakan data acak karena tidak ada kolom 'genre' di database
        $categories = [
            'populer'  => Movie::latest()->limit(10)->get(),
            'action'   => Movie::inRandomOrder()->limit(10)->get(),
            'drama'    => Movie::inRandomOrder()->limit(10)->get(),
            'thriller' => Movie::inRandomOrder()->limit(10)->get(),
            'comedy'   => Movie::inRandomOrder()->limit(10)->get(),
        ];

        // Menyisipkan URL poster ke setiap objek film menggunakan mapping
        foreach ($categories as $key => $movies) {
            $categories[$key] = $movies->map(function ($movie) {
                $movie->poster_url = $this->getMoviePoster($movie->title);
                return $movie;
            });
        }
        
        $data = $categories;
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

                    foreach ($titles as $title) {
                        $dbMovie = Movie::where('title', $title)->first();
                        $movieId = $dbMovie ? $dbMovie->movie_id : null;
                        $inList = $movieId ? \App\Models\MyList::where('user_id', auth()->id())->where('movie_id', $movieId)->exists() : false;

                        $recommendations[] = [
                            'title' => $title,
                            'poster' => $this->getMoviePoster($title),
                            'movie_id' => $movieId,
                            'inList' => $inList
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
        $movie = Movie::where('movie_id', $movie_id)->firstOrFail();
        $inList = \App\Models\MyList::where('user_id', auth()->id())->where('movie_id', $movie->movie_id)->exists();
        return view('movie_detail', compact('movie', 'inList'));
    }

    public function detail($id)
    {
        $movie = Movie::findOrFail($id);
        $inList = \App\Models\MyList::where('user_id', auth()->id())->where('movie_id', $movie->movie_id)->exists();
        return view('movie_detail', compact('movie', 'inList'));
    }
}