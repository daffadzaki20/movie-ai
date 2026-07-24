@extends('layouts.app')

@section('title', 'Hasil Rekomendasi AI - MOVIEAI')

@section('content')
    <div class="px-12 max-w-7xl mx-auto mb-12">
        <h1 class="text-4xl font-extrabold mb-4 border-l-4 border-red-600 pl-4 text-white">Movie Recommender AI</h1>
        <p class="text-slate-400 pl-5">Hasil analisis kecerdasan buatan untuk selera film Anda.</p>
    </div>

    <div class="max-w-4xl mx-auto px-6">
        <!-- Input Section -->
        <form action="/recommend" method="GET" class="flex gap-4 p-2 bg-slate-800/50 rounded-2xl border border-slate-700 shadow-xl mb-10">
            <input type="text" name="movie" value="{{ $searchedMovie ?? '' }}"
                   class="flex-1 p-4 rounded-xl bg-transparent focus:outline-none text-lg" 
                   placeholder="Cari judul film lain..." required>
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-500 px-8 py-4 rounded-xl font-bold transition">
                Cari Lagi
            </button>
        </form>

        <!-- Error Handling -->
        @if(isset($error) && $error)
            <div class="p-6 bg-red-900/20 border border-red-800 text-red-400 rounded-2xl text-center mb-8">
                {{ $error }}
            </div>
        @endif

        <!-- FILM UTAMA YANG DICARI (Lengkap dengan Tombol My List) -->
        @if(isset($searchedMovie) && (!isset($error) || !$error))
            <div class="mb-8">
                <h3 class="text-xl font-bold mb-3 text-slate-300">Film yang Anda Cari:</h3>
                <div class="p-5 bg-indigo-950/40 glass rounded-2xl border border-indigo-500/50 flex items-center gap-6 shadow-2xl">
                    <!-- Poster Film Utama -->
                    <a href="{{ isset($searchedMovieId) && $searchedMovieId ? url('/movie/' . $searchedMovieId) : '#' }}" class="w-24 h-36 flex-shrink-0 overflow-hidden rounded-xl bg-slate-700 shadow-lg block">
                        @if(isset($searchedPoster) && $searchedPoster)
                            <img src="{{ $searchedPoster }}" alt="{{ $searchedMovie }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-500 text-center p-1">No Poster</div>
                        @endif
                    </a>
                    
                    <!-- Judul Film Utama -->
                    <div class="flex-1">
                        <span class="bg-indigo-600 text-xs px-3 py-1 rounded-full font-bold uppercase tracking-wider inline-block mb-2">Pencarian Utama</span>
                        <a href="{{ isset($searchedMovieId) && $searchedMovieId ? url('/movie/' . $searchedMovieId) : '#' }}">
                            <h2 class="text-3xl font-extrabold text-white hover:text-indigo-300 transition">{{ $searchedMovie }}</h2>
                        </a>
                    </div>

                    <!-- Tombol Tambah ke My List untuk Film Utama -->
                    @if(isset($searchedMovieId) && $searchedMovieId)
                    <button class="myListBtn bg-slate-700 hover:bg-slate-600 w-12 h-12 rounded-full transition-all flex items-center justify-center flex-shrink-0 border {{ isset($searchedInList) && $searchedInList ? 'border-green-500' : 'border-transparent' }}" data-movie-id="{{ $searchedMovieId }}">
                        @if(isset($searchedInList) && $searchedInList)
                            <span class="text-green-400 font-bold text-lg">✓</span>
                        @else
                            <span class="text-white font-bold text-lg">+</span>
                        @endif
                    </button>
                    @endif
                </div>
            </div>
        @endif

        <!-- Results Section (Rekomendasi Film Serupa) -->
        @if(isset($recommendations) && $recommendations)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-in fade-in duration-700">
                <div class="col-span-full mb-2">
                    <h3 class="text-2xl font-bold">Rekomendasi Film Serupa:</h3>
                </div>
                
                @foreach($recommendations as $movie)
                    <div class="p-4 bg-slate-800/60 glass rounded-2xl border border-slate-700 flex items-center gap-6 hover:border-indigo-500 transition group">
                        <!-- Poster Film Rekomendasi -->
                        <a href="{{ isset($movie['movie_id']) && $movie['movie_id'] ? url('/movie/' . $movie['movie_id']) : '#' }}" class="w-20 h-28 flex-shrink-0 overflow-hidden rounded-lg bg-slate-700 shadow-lg block">
                            @if(isset($movie['poster']) && $movie['poster'])
                                <img src="{{ $movie['poster'] }}" alt="{{ $movie['title'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-500 text-center p-1">No Poster</div>
                            @endif
                        </a>
                        
                        <!-- Judul -->
                        <div class="flex-1">
                            <span class="text-indigo-400 font-bold text-sm block mb-1">#{{ $loop->iteration }}</span>
                            <a href="{{ isset($movie['movie_id']) && $movie['movie_id'] ? url('/movie/' . $movie['movie_id']) : '#' }}">
                                <h4 class="text-lg font-bold group-hover:text-indigo-300 transition">{{ $movie['title'] }}</h4>
                            </a>
                        </div>

                        <!-- Tombol Tambah ke My List -->
                        @if(isset($movie['movie_id']) && $movie['movie_id'])
                        <button class="myListBtn bg-slate-700 hover:bg-slate-600 w-10 h-10 rounded-full transition-all flex items-center justify-center flex-shrink-0 border {{ isset($movie['inList']) && $movie['inList'] ? 'border-green-500' : 'border-transparent' }}" data-movie-id="{{ $movie['movie_id'] }}">
                            @if(isset($movie['inList']) && $movie['inList'])
                                <span class="text-green-400 font-bold">✓</span>
                            @else
                                <span class="text-white font-bold">+</span>
                            @endif
                        </button>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            @if(!isset($error) || !$error)
                <div class="text-center py-20 text-slate-500">
                    <p>Masukkan judul film di atas untuk melihat rekomendasi AI.</p>
                </div>
            @endif
        @endif
    </div>


@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.myListBtn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const movieId = this.getAttribute('data-movie-id');
                
                fetch('/my-list', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ movie_id: movieId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        if (data.action === 'added') {
                            this.innerHTML = '<span class="text-green-400 font-bold text-lg">✓</span>';
                            this.classList.add('border-green-500');
                            this.classList.remove('border-transparent');
                        } else {
                            this.innerHTML = '<span class="text-white font-bold text-lg">+</span>';
                            this.classList.add('border-transparent');
                            this.classList.remove('border-green-500');
                        }
                    }
                })
                .catch(err => console.error(err));
            });
        });
    </script>
@endsection