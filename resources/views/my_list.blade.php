@extends('layouts.app')

@section('title', 'My List - MOVIEAI')

@section('content')
    <div class="px-12 max-w-7xl mx-auto">
        <h1 class="text-4xl font-extrabold mb-8 border-l-4 border-red-600 pl-4">My List</h1>
        
        @if($movies->isEmpty())
            <div class="text-center py-20">
                <div class="text-slate-500 text-6xl mb-4">🎬</div>
                <h2 class="text-2xl font-bold text-slate-400 mb-2">Daftar Anda Masih Kosong</h2>
                <p class="text-slate-500 mb-6">Tambahkan film dan acara TV yang ingin Anda tonton agar lebih mudah ditemukan.</p>
                <a href="/dashboard" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-lg transition">Jelajahi Film</a>
            </div>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
                @foreach($movies as $movie)
                <div class="group relative aspect-[2/3] rounded-lg overflow-hidden shadow-lg transition duration-300 transform hover:scale-105" id="movie-card-{{ $movie->movie_id }}">
                    <!-- Poster -->
                    @if(isset($movie->poster_url) && $movie->poster_url)
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-slate-800 flex items-center justify-center p-4 text-center">
                            <span class="font-bold text-sm">{{ $movie->title }}</span>
                        </div>
                    @endif

                    <!-- Hover Overlay -->
                    <div class="absolute inset-0 bg-black/80 opacity-0 group-hover:opacity-100 transition flex flex-col justify-between p-4">
                        <div class="flex justify-end">
                            <button class="removeBtn text-white hover:text-red-500 bg-slate-800/80 hover:bg-slate-800 p-2 rounded-full transition" data-movie-id="{{ $movie->movie_id }}" title="Hapus dari My List">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        <a href="{{ url('/movie/detail', $movie->id) }}" class="block">
                            <h4 class="font-bold text-sm mb-2 leading-tight">{{ $movie->title }}</h4>
                            <span class="text-red-500 text-xs font-bold uppercase tracking-wider hover:text-red-400">Lihat Detail →</span>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('.removeBtn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
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
                    if (data.success && data.action === 'removed') {
                        const card = document.getElementById('movie-card-' + movieId);
                        if (card) {
                            card.style.opacity = '0';
                            setTimeout(() => card.remove(), 300);
                        }
                    }
                })
                .catch(err => console.error(err));
            });
        });
    </script>
@endsection
