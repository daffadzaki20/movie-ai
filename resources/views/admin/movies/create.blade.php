@extends('admin.layout.app')

@section('content')
<div class="max-w-2xl bg-gray-800 p-8 rounded-lg shadow-xl border border-gray-700 mx-auto">
    <h2 class="text-2xl font-bold mb-6">Tambah Film Baru</h2>
    
    <form action="{{ route('admin.movies.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block mb-2 text-sm text-gray-300">Judul Film</label>
            <input type="text" name="title" class="w-full p-2.5 rounded bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-red-500" required>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm text-gray-300">Overview / Sinopsis</label>
            <textarea name="overview" rows="4" class="w-full p-2.5 rounded bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-red-500" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm text-gray-300">Genre (Contoh: Action, Drama)</label>
            <input type="text" name="genres" class="w-full p-2.5 rounded bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-red-500" required>
        </div>

        <!-- Tambahan Input Poster -->
        <div class="mb-4">
            <label class="block mb-2 text-sm text-gray-300">Upload Poster Film (Format: JPG, PNG)</label>
            <input type="file" name="poster" class="w-full p-2 text-sm bg-gray-700 border border-gray-600 text-gray-300 rounded cursor-pointer focus:outline-none" required>
        </div>

        <!-- Tambahan Input Link Trailer YouTube -->
        <div class="mb-4">
            <label class="block mb-2 text-sm text-gray-300">Link Trailer YouTube</label>
            <input type="url" name="trailer_url" placeholder="https://www.youtube.com/watch?v=..." class="w-full p-2.5 rounded bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-red-500" required>
        </div>

        <div class="mb-6">
            <label class="block mb-2 text-sm text-gray-300">Popularitas (Angka)</label>
            <input type="number" step="0.01" name="popularity" class="w-full p-2.5 rounded bg-gray-700 border border-gray-600 text-white focus:outline-none focus:border-red-500" required>
        </div>

        <div class="flex justify-between items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-white transition">Kembali</a>
            <button type="submit" class="bg-red-600 hover:bg-red-700 px-6 py-2.5 rounded-lg font-semibold transition">Simpan Film</button>
        </div>
    </form>
</div>
@endsection