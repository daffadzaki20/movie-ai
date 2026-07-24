@extends('layouts.app')

@section('title', 'Dashboard - MOVIEAI')

@section('content')
    <!-- Hero Section -->
    <div class="relative w-full h-[70vh] flex items-center px-12 bg-slate-900 overflow-hidden -mt-24">
        <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-transparent z-10"></div>
        <div class="relative z-20 max-w-2xl mt-16">
            <h1 class="text-7xl font-extrabold mb-6 tracking-tighter leading-tight">Temukan <br><span class="text-red-600">Film Favoritmu</span></h1>
            <p class="text-xl text-slate-300 mb-8">Dapatkan rekomendasi cerdas berbasis AI yang disesuaikan dengan selera unikmu.</p>
            <div class="flex gap-4">
                <a href="/recommend" class="bg-red-600 text-white px-8 py-4 font-bold rounded-lg hover:bg-red-700 transition transform hover:scale-105">Mulai Rekomendasi</a>
            </div>
        </div>
    </div>

    <!-- Container untuk Kategori -->
    <div class="py-10">
        {{-- Kita panggil kategori di sini. Pastikan controller Anda mengirim $data --}}
        @include('components.movie-row', ['title' => 'Populer Sekarang', 'id' => 'row1', 'movies' => $data['populer']])
        @include('components.movie-row', ['title' => 'Action', 'id' => 'row2', 'movies' => $data['action']])
        @include('components.movie-row', ['title' => 'Drama', 'id' => 'row3', 'movies' => $data['drama']])
        @include('components.movie-row', ['title' => 'Thriller', 'id' => 'row4', 'movies' => $data['thriller']])
    </div>
@endsection