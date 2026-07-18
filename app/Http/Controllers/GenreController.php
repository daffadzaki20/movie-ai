<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
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

    public function show($name)
    {
        // Get movies with this genre, paginated
        $movies = Movie::where('genres', 'like', '%' . $name . '%')
                       ->orderBy('popularity', 'desc')
                       ->paginate(24);
                       
        // Fetch posters for current page
        $movieController = new MovieRecommendationController();
        foreach ($movies as $movie) {
            $movie->poster_url = $movieController->getMoviePoster($movie->title);
            $movie->inList = \App\Models\MyList::where('user_id', auth()->id())->where('movie_id', $movie->movie_id)->exists();
        }

        return view('genres.show', compact('movies', 'name'));
    }
}
