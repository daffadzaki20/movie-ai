<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MyList;
use App\Models\Movie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MyListController extends Controller
{
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

    public function index()
    {
        $user = Auth::user();
        
        // Fetch movies saved by user
        $movies = $user->myListMovies()->latest('my_lists.created_at')->get();
        
        // Append poster_url
        $movies = $movies->map(function ($movie) {
            $movie->poster_url = $this->getMoviePoster($movie->title);
            return $movie;
        });

        return view('my_list', compact('movies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required|integer'
        ]);

        $user = Auth::user();
        $movieId = $request->movie_id;

        // Check if movie exists in the movies table (from TMDB CSV)
        $movie = Movie::where('movie_id', $movieId)->first();
        if (!$movie) {
            return response()->json(['success' => false, 'message' => 'Movie not found.'], 404);
        }

        // Check if already in list
        $exists = MyList::where('user_id', $user->id)->where('movie_id', $movieId)->exists();
        
        if ($exists) {
            // Remove it (toggle)
            MyList::where('user_id', $user->id)->where('movie_id', $movieId)->delete();
            return response()->json(['success' => true, 'action' => 'removed']);
        } else {
            // Add it
            MyList::create([
                'user_id' => $user->id,
                'movie_id' => $movieId
            ]);
            return response()->json(['success' => true, 'action' => 'added']);
        }
    }
}
