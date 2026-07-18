<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animasi Fade In */
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-black text-white font-sans overflow-x-hidden">

    <!-- Background Dekoratif -->
    <div class="fixed top-0 left-0 w-full h-full bg-slate-950 -z-10"></div>
    <div class="fixed top-0 left-0 w-full h-1/2 bg-gradient-to-b from-red-900/20 to-transparent -z-10"></div>

    <div class="max-w-6xl mx-auto p-12 fade-in">
        <a href="/dashboard" class="text-red-500 hover:text-white transition flex items-center mb-10 font-bold">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Dashboard
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <div class="lg:col-span-2">
                <h1 id="title" class="text-7xl font-extrabold mb-6 leading-tight">{{ $movie->title }}</h1>
                
                <div class="bg-white/5 backdrop-blur-lg border border-white/10 p-8 rounded-3xl mb-8">
                    <h2 class="text-red-500 font-bold uppercase tracking-widest mb-4">Sinopsis</h2>
                    <p class="text-slate-300 text-xl leading-relaxed">{{ $movie->overview }}</p>
                </div>
            </div>

    
            <div class="space-y-6">
                <div class="bg-gradient-to-br from-slate-900 to-black p-8 rounded-3xl border border-white/10 shadow-2xl">
                    <h3 class="text-xl font-bold mb-6">Aksi Film</h3>
                    <div class="space-y-4">
                        <a id="trailerBtn" href="https://www.youtube.com/results?search_query={{ urlencode($movie->title) }}+trailer" 
                           target="_blank"
                           class="block text-center bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl transition-all hover:scale-105 active:scale-95 shadow-lg shadow-red-600/30">
                           Tonton Trailer
                        </a>

                        <button id="myListBtn" data-movie-id="{{ $movie->movie_id }}"
                                class="w-full text-center font-bold py-4 rounded-xl transition-all hover:scale-105 active:scale-95 border-2 {{ isset($inList) && $inList ? 'bg-white/20 border-white/50 text-white' : 'bg-transparent border-red-600 text-red-500 hover:bg-red-600/10' }}">
                            {{ isset($inList) && $inList ? '✓ Hapus dari My List' : '+ Tambah ke My List' }}
                        </button>
                    </div>
                </div>

                <div class="bg-white/5 p-6 rounded-3xl border border-white/5">
                    <span class="text-slate-500 text-sm font-bold">MOVIE ID</span>
                    <p class="text-2xl font-mono">{{ $movie->movie_id }}</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Sedikit efek JS untuk interaksi
        const btn = document.getElementById('trailerBtn');
        btn.addEventListener('mouseover', () => {
            btn.classList.add('shadow-red-500/50');
        });
        btn.addEventListener('mouseout', () => {
            btn.classList.remove('shadow-red-500/50');
        });

        // Add to My List AJAX
        const myListBtn = document.getElementById('myListBtn');
        if (myListBtn) {
            myListBtn.addEventListener('click', function() {
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
                            this.innerText = '✓ Hapus dari My List';
                            this.className = "w-full text-center font-bold py-4 rounded-xl transition-all hover:scale-105 active:scale-95 border-2 bg-white/20 border-white/50 text-white";
                        } else {
                            this.innerText = '+ Tambah ke My List';
                            this.className = "w-full text-center font-bold py-4 rounded-xl transition-all hover:scale-105 active:scale-95 border-2 bg-transparent border-red-600 text-red-500 hover:bg-red-600/10";
                        }
                    }
                })
                .catch(err => console.error(err));
            });
        }
    </script>
</body>
</html>