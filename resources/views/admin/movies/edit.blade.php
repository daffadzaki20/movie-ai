@extends('admin.layout.app')

@section('content')
<div class="max-w-2xl bg-gray-800 p-8 rounded-lg shadow-xl border border-gray-700 mx-auto">
    <h2 class="text-2xl font-bold mb-6">Edit Film</h2>
    
    <form action="{{ route('admin.movies.update', $movie->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-2 text-sm text-gray-300">Judul Film</label>
            <input type="text" name="title" value="{{ $movie->title }}" class="w-full p-2.5 rounded bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-blue-500" required>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm text-gray-300">Overview / Sinopsis</label>
            <textarea name="overview" rows="4" class="w-full p-2.5 rounded bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-blue-500" required>{{ $movie->overview }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm text-gray-300">Genre</label>
            <input type="text" name="genres" value="{{ $movie->genres }}" class="w-full p-2.5 rounded bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-blue-500" required>
        </div>

        <!-- Tampilkan Poster Lama & Ganti Poster -->
        <div class="mb-4">
            <label class="block mb-2 text-sm text-gray-300">Poster Saat Ini</label>
            @if($movie->poster)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $movie->poster) }}" alt="Poster" class="w-24 h-32 object-cover rounded border border-gray-600">
                </div>
            @endif
            <label class="block mb-1 text-xs text-gray-400">Ganti Poster (Opsional, biarkan kosong jika tidak ingin mengubah)</label>
            <input type="file" name="poster" class="w-full p-2 text-sm bg-gray-700 border border-gray-600 text-gray-300 rounded cursor-pointer focus:outline-none">
        </div>

        <!-- Input Link Trailer YouTube -->
        <div class="mb-4">
            <label class="block mb-2 text-sm text-gray-300">Link Trailer YouTube</label>
            <input type="url" name="trailer_url" value="{{ $movie->trailer_url }}" class="w-full p-2.5 rounded bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-blue-500" required>
        </div>

        <div class="mb-6">
            <label class="block mb-2 text-sm text-gray-300">Popularitas</label>
            <input type="number" step="0.01" name="popularity" value="{{ $movie->popularity }}" class="w-full p-2.5 rounded bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-blue-500" required>
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition">Kembali</a>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 px-6 py-2.5 rounded-lg font-semibold transition">Perbarui Film</button>
        </div>
    </form>
</div>
@endsection