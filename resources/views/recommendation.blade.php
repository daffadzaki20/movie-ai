<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Rekomendasi AI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(12px); }
        .gradient-text { background: linear-gradient(to right, #818cf8, #c084fc); -webkit-background-clip: text; color: transparent; }
    </style>
</head>
<body class="bg-slate-950 text-white min-h-screen p-6 md:p-12">

    <div class="max-w-4xl mx-auto mb-12 text-center">
        <h1 class="text-5xl font-extrabold mb-4 gradient-text">Movie Recommender AI</h1>
        <p class="text-slate-400">Hasil analisis kecerdasan buatan untuk selera film Anda.</p>
    </div>

    <div class="max-w-4xl mx-auto">
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
        @if($error)
            <div class="p-6 bg-red-900/20 border border-red-800 text-red-400 rounded-2xl text-center mb-8">
                {{ $error }}
            </div>
        @endif

        <!-- Results Section -->
        @if($recommendations)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 animate-in fade-in duration-700">
                <div class="col-span-full mb-4">
                    <h3 class="text-2xl font-bold">Rekomendasi untuk: <span class="text-indigo-400 italic">"{{ $searchedMovie }}"</span></h3>
                </div>
                
                @foreach($recommendations as $movie)
                    <div class="p-4 bg-slate-800/60 glass rounded-2xl border border-slate-700 flex items-center gap-6 hover:border-indigo-500 transition group">
                        <!-- Poster Film -->
                        <div class="w-20 h-28 flex-shrink-0 overflow-hidden rounded-lg bg-slate-700 shadow-lg">
                            @if($movie['poster'])
                                <img src="{{ $movie['poster'] }}" alt="{{ $movie['title'] }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-[10px] text-slate-500 text-center p-1">No Poster</div>
                            @endif
                        </div>
                        
                        <!-- Judul -->
                        <div>
                            <span class="text-indigo-400 font-bold text-sm block mb-1">#{{ $loop->iteration }}</span>
                            <h4 class="text-lg font-bold group-hover:text-indigo-300 transition">{{ $movie['title'] }}</h4>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            @if(!$error)
                <div class="text-center py-20 text-slate-500">
                    <p>Masukkan judul film di atas untuk melihat rekomendasi AI.</p>
                </div>
            @endif
        @endif
    </div>

    <div class="text-center mt-16">
        <a href="/dashboard" class="text-slate-500 hover:text-white transition underline underline-offset-8">Kembali ke Dashboard</a>
    </div>

</body>
</html>