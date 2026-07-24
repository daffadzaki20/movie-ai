@extends('layouts.app')

@section('title', 'Pilih Genre - MOVIEAI')

@section('content')
    <div class="px-12 max-w-7xl mx-auto">
        <h1 class="text-4xl font-extrabold mb-8 border-l-4 border-red-600 pl-4 text-white">Jelajahi Berdasarkan Genre</h1>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($genres as $genre)
            <a href="{{ route('genre.show', $genre) }}" class="group relative h-40 rounded-xl overflow-hidden cursor-pointer shadow-lg hover:ring-4 hover:ring-indigo-500 transition duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 to-slate-900 opacity-80 group-hover:opacity-100 transition duration-300"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <h3 class="text-2xl font-bold text-white group-hover:scale-110 transition duration-300">{{ $genre }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
@endsection
