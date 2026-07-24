@extends('layouts.app')

@section('title', 'Film Genre ' . $name . ' - MOVIEAI')

@section('content')
    <div class="px-12 max-w-7xl mx-auto">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('genre.index') }}" class="bg-slate-800 p-2 rounded-full hover:bg-slate-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-3xl font-bold">Film Genre: <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">{{ $name }}</span></h1>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6">
            @foreach($movies as $movie)
            <a href="{{ url('/movie/detail', $movie->id) }}" class="group relative aspect-[2/3] rounded-lg overflow-hidden cursor-pointer shadow-lg hover:ring-4 hover:ring-indigo-500 transition duration-300">
                @if(isset($movie->poster_url) && $movie->poster_url)
                    <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-slate-800 flex items-center justify-center p-4 text-center">
                        <span class="font-bold text-sm">{{ $movie->title }}</span>
                    </div>
                @endif

                <div class="absolute inset-0 bg-black/80 opacity-0 group-hover:opacity-100 transition p-4 flex flex-col justify-end">
                    <h4 class="font-bold text-sm mb-2">{{ $movie->title }}</h4>
                    @if(isset($movie->inList) && $movie->inList)
                        <span class="text-xs text-green-400">✓ Tersimpan di My List</span>
                    @endif
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-12 flex justify-center">
            {{ $movies->links('pagination::tailwind') }}
        </div>
    </div>
@endsection
