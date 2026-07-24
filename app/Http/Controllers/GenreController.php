<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\MyList;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MovieRecommendationController;

class GenreController extends Controller
{
    public function index()
    {
        $genres = [
            'Action', 'Adventure', 'Animation', 'Comedy', 'Crime', 
            'Documentary', 'Drama', 'Family', 'Fantasy', 'History', 
            'Horror', 'Music', 'Mystery', 'Romance', 'Science Fiction', 
            'Thriller', 'War', 'Western'
        ];
        
        return view('genres.index', compact('genres'));
    }

    public function show(string $name)
    {
        // Get movies with this genre, paginated
        $movies = Movie::where('genres', 'like', '%' . $name . '%')
                    ->orderBy('popularity', 'desc')
                    ->paginate(24);
                    
        // Fetch posters for current page
        $movieController = new MovieRecommendationController();
        $userId = Auth::id(); // Menggunakan Auth::id() agar seragam

        foreach ($movies as $movie) {
            $movie->poster_url = $movieController->getMoviePoster($movie->title);
            $movie->inList = $userId ? MyList::where('user_id', $userId)->where('movie_id', $movie->movie_id)->exists() : false;
        }

        return view('genres.show', compact('movies', 'name'));
    }
}