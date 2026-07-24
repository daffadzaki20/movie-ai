<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $movie->title }} - Detail Film</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Animasi Fade In */
        .fade-in { animation: fadeIn 0.8s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-black text-white font-sans overflow-x-hidden selection:bg-red-600 selection:text-white">

    <!-- Background Dekoratif -->
    <div class="fixed top-0 left-0 w-full h-full bg-slate-950 -z-10"></div>
    <div class="fixed top-0 left-0 w-full h-1/2 bg-gradient-to-b from-red-900/20 to-transparent -z-10"></div>

    <div class="max-w-6xl mx-auto p-8 lg:p-12 fade-in space-y-10">
        
        <!-- Tombol Kembali -->
        <div>
            <a href="/dashboard" class="inline-flex items-center text-red-500 hover:text-white transition font-bold group">
                <svg class="w-5 h-5 mr-2 transform group-hover:-translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke Dashboard
            </a>
        </div>

        <!-- BAGIAN ATAS (Poster di Kiri, Judul + Sinopsis + Durasi/Genre + Aksi di Kanan) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 items-start">
            
            <!-- KOLOM KIRI: Poster Film -->
            <div class="lg:col-span-1">
                <div class="bg-white/5 p-4 rounded-3xl border border-white/10 shadow-2xl overflow-hidden sticky top-8">
                    <img id="movie-poster" src="" alt="Poster Film" class="w-full h-auto rounded-2xl object-cover shadow-lg">
                </div>
            </div>

            <!-- KOLOM KANAN: Judul, Rating, Durasi, Genre, Sinopsis, & Tombol Aksi -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Judul & Rating -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-white/10 pb-6">
                    <h1 id="title" class="text-4xl lg:text-5xl font-extrabold tracking-tight">{{ $movie->title }}</h1>
                    
                    <!-- Rating Badge -->
                    <div class="flex items-center bg-white/5 border border-white/10 px-5 py-3 rounded-2xl w-fit shrink-0">
                        <span class="text-yellow-500 text-xl mr-2">⭐</span>
                        <div>
                            <span class="text-xs text-slate-400 block uppercase tracking-wider font-bold">Rating TMDB</span>
                            <span id="movie-rating" class="text-lg font-bold">Memuat...</span>
                        </div>
                    </div>
                </div>

                <!-- Informasi Tambahan (Durasi & Genre) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <!-- Durasi Film -->
                    <div class="bg-white/5 border border-white/10 px-5 py-3 rounded-2xl">
                        <span class="text-xs text-slate-400 block uppercase tracking-wider font-bold">Durasi</span>
                        <span id="movie-runtime" class="text-base font-bold text-white">Memuat...</span>
                    </div>

                    <!-- Genre Film -->
                    <div class="bg-white/5 border border-white/10 px-5 py-3 rounded-2xl">
                        <span class="text-xs text-slate-400 block uppercase tracking-wider font-bold">Genre</span>
                        <span id="movie-genres" class="text-base font-bold text-white">Memuat...</span>
                    </div>
                </div>

                <!-- Kotak Sinopsis -->
                <div class="bg-white/5 backdrop-blur-lg border border-white/10 p-6 lg:p-8 rounded-3xl space-y-3">
                    <h2 class="text-red-500 font-bold uppercase tracking-widest text-sm">Sinopsis</h2>
                    <p id="movie-overview" class="text-slate-300 text-base lg:text-lg leading-relaxed">Memuat sinopsis...</p>
                </div>

                <!-- Tombol Aksi & Movie ID -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2">
                    <!-- Tombol My List -->
                    <button id="myListBtn" data-movie-id="{{ $movie->movie_id }}"
                            class="w-full text-center font-bold py-4 px-6 rounded-2xl transition-all hover:scale-105 active:scale-95 border-2 shadow-lg flex items-center justify-center {{ isset($inList) && $inList ? 'bg-white/20 border-white/50 text-white' : 'bg-transparent border-red-600 text-red-500 hover:bg-red-600/10' }}">
                        {{ isset($inList) && $inList ? '✓ Hapus dari My List' : '+ Tambah ke My List' }}
                    </button>

                    <!-- Kotak Movie ID -->
                    <div class="bg-white/5 border border-white/10 px-6 py-4 rounded-2xl flex flex-col justify-center">
                        <span class="text-slate-500 text-xs font-bold uppercase tracking-wider">Movie ID</span>
                        <span class="text-lg font-mono font-bold">{{ $movie->movie_id }}</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- BAGIAN BAWAH: Official Trailer (Full Width Lebar) -->
        <div class="bg-white/5 backdrop-blur-lg border border-white/10 p-6 lg:p-8 rounded-3xl space-y-4">
            <h2 class="text-red-500 font-bold uppercase tracking-widest text-sm">Official Trailer</h2>
            
            <div id="trailer-container" class="relative w-full overflow-hidden rounded-2xl shadow-2xl bg-black" style="padding-top: 56.25%;">
                <div class="absolute inset-0 flex items-center justify-center text-slate-500 italic">
                    Memuat trailer dari TMDB...
                </div>
            </div>
        </div>

    </div>

    <script>
        const movieId = "{{ $movie->movie_id }}";
        const apiKey = "527f6b11a69b31736bbbeede25f614b1"; 

        // Validasi awal agar tidak terjadi invalid parameters
        if (!movieId || movieId.trim() === "") {
            console.error("Movie ID tidak ditemukan!");
        } else {
            // 1. Fetch Detail Film (Poster, Rating, Sinopsis, Durasi, & Genre) dari TMDB
            fetch(`https://api.themoviedb.org/3/movie/${movieId}?api_key=${apiKey}&language=en-US`)
                .then(res => res.json())
                .then(data => {
                    if (data.success === false) {
                        console.error("TMDB Error:", data.status_message);
                        return;
                    }

                    // Set Poster
                    if (data.poster_path) {
                        document.getElementById('movie-poster').src = `https://image.tmdb.org/t/p/w500${data.poster_path}`;
                    } else {
                        document.getElementById('movie-poster').alt = "Poster tidak tersedia";
                    }

                    // Set Rating
                    if (data.vote_average) {
                        document.getElementById('movie-rating').innerText = `${data.vote_average.toFixed(1)} / 10`;
                    } else {
                        document.getElementById('movie-rating').innerText = "N/A";
                    }

                    // Set Durasi
                    if (data.runtime) {
                        const hours = Math.floor(data.runtime / 60);
                        const minutes = data.runtime % 60;
                        document.getElementById('movie-runtime').innerText = hours > 0 ? `${hours}j ${minutes}m` : `${minutes} menit`;
                    } else {
                        document.getElementById('movie-runtime').innerText = "N/A";
                    }

                    // Set Genre
                    if (data.genres && data.genres.length > 0) {
                        const genreNames = data.genres.map(genre => genre.name).join(', ');
                        document.getElementById('movie-genres').innerText = genreNames;
                    } else {
                        document.getElementById('movie-genres').innerText = "N/A";
                    }

                    // Set Sinopsis
                    if (data.overview) {
                        document.getElementById('movie-overview').innerText = data.overview;
                    } else {
                        document.getElementById('movie-overview').innerText = "Sinopsis belum tersedia untuk film ini.";
                    }
                })
                .catch(err => {
                    console.error('Gagal memuat detail tambahan:', err);
                });

            // 2. Fetch Trailer TMDB Otomatis
            fetch(`https://api.themoviedb.org/3/movie/${movieId}/videos?api_key=${apiKey}&language=en-US`)
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('trailer-container');
                    if (data.results) {
                        const trailer = data.results.find(vid => vid.site === 'YouTube' && vid.type === 'Trailer');
                        
                        if (trailer) {
                            container.innerHTML = `
                                <iframe class="absolute top-0 left-0 w-full h-full rounded-2xl"
                                    src="https://www.youtube.com/embed/${trailer.key}" 
                                    title="YouTube video player" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            `;
                            return;
                        }
                    }
                    container.innerHTML = `
                        <div class="absolute inset-0 flex items-center justify-center text-slate-500 italic p-4 text-center">
                            Trailer resmi tidak ditemukan di TMDB.
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Gagal memuat trailer:', error);
                    document.getElementById('trailer-container').innerHTML = `
                        <div class="absolute inset-0 flex items-center justify-center text-slate-500 italic p-4 text-center">
                            Gagal memuat trailer.
                        </div>
                    `;
                });
        }

        // 3. Script Add to My List AJAX
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
                            this.className = "w-full text-center font-bold py-4 px-6 rounded-2xl transition-all hover:scale-105 active:scale-95 border-2 shadow-lg flex items-center justify-center bg-white/25 border-white/60 text-white";
                        } else {
                            this.innerText = '+ Tambah ke My List';
                            this.className = "w-full text-center font-bold py-4 px-6 rounded-2xl transition-all hover:scale-105 active:scale-95 border-2 shadow-lg flex items-center justify-center bg-transparent border-red-600 text-red-500 hover:bg-red-600/10";
                        }
                    }
                })
                .catch(err => console.error(err));
            });
        }
    </script>
</body>
</html>