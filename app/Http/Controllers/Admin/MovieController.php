<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::all();
        return view('admin.movies.index', compact('movies'));
    }

    public function dashboard()
    {
        $totalMovies = Movie::count();
        return view('admin.dashboard', compact('totalMovies'));
    }

    public function create()
    {
        return view('admin.movies.create');
    }

    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();

        return redirect()->route('admin.movies.index')
            ->with('success', 'Film berhasil dihapus!');
    }
}