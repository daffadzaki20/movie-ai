<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\Cache;

class AdminController extends Controller
{
    // Menampilkan daftar film di dashboard admin
    public function index()
    {
        $movies = Movie::latest()->paginate(10);
        return view('admin.dashboard', compact('movies'));
    }

    // Form tambah film
    public function create()
    {
        return view('admin.movies.create');
    }

    // Menyimpan film baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'overview'   => 'required|string',
            'genres'     => 'required|string',
            'popularity' => 'required|numeric',
        ]);

        Movie::create([
            'movie_id'   => rand(1000, 99999),
            'title'      => $request->title,
            'overview'   => $request->overview,
            'genres'     => $request->genres,
            'popularity' => $request->popularity,
        ]);

        Cache::forget('dashboard_movies_data');
        return redirect()->route('admin.dashboard')->with('success', 'Film berhasil ditambahkan!');
    }

    // Form edit film
    public function edit($id)
    {
        $movie = Movie::findOrFail($id);
        return view('admin.movies.edit', compact('movie'));
    }

    // Memperbarui data film
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'overview'   => 'required|string',
            'genres'     => 'required|string',
            'popularity' => 'required|numeric',
        ]);

        $movie = Movie::findOrFail($id);
        $movie->update([
            'title'      => $request->title,
            'overview'   => $request->overview,
            'genres'     => $request->genres,
            'popularity' => $request->popularity,
        ]);

        Cache::forget('dashboard_movies_data');
        return redirect()->route('admin.dashboard')->with('success', 'Film berhasil diperbarui!');
    }

    // Menghapus film
    public function destroy($id)
    {
        $movie = Movie::findOrFail($id);
        $movie->delete();

        Cache::forget('dashboard_movies_data');
        return redirect()->route('admin.dashboard')->with('success', 'Film berhasil dihapus!');
    }
}