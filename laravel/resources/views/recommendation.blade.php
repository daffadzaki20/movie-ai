<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Movie Recommender</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white min-h-screen flex items-center justify-center">

    <div class="max-w-2xl w-full bg-slate-800 p-8 rounded-2xl shadow-2xl border border-slate-700">
        <h1 class="text-3xl font-bold text-center mb-6 text-indigo-400">Movie Recommender AI</h1>
        
        <form action="/recommend" method="GET" class="flex gap-2">
            <input type="text" name="movie" 
                   class="flex-1 p-3 rounded-lg bg-slate-700 border border-slate-600 focus:outline-none focus:ring-2 focus:ring-indigo-500" 
                   placeholder="Masukkan judul film..." required>
            <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 px-6 py-3 rounded-lg font-semibold transition">
                Cari
            </button>
        </form>

        @if($error)
            <div class="mt-6 p-4 bg-red-900/30 border border-red-800 text-red-400 rounded-lg text-center">
                {{ $error }}
            </div>
        @endif

        @if($recommendations)
            <div class="mt-8 animate-fade-in">
                <h3 class="text-xl font-semibold mb-4">Hasil rekomendasi untuk: <span class="text-indigo-300">{{ $searchedMovie }}</span></h3>
                <div class="grid gap-3">
                    @foreach($recommendations as $movie)
                        <div class="p-4 bg-slate-700 rounded-lg hover:bg-slate-600 transition flex items-center">
                            <span class="text-indigo-400 font-bold mr-4">#{{ $loop->iteration }}</span>
                            {{ $movie }}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="mt-4">
    <a href="/" class="text-slate-400 hover:text-white text-sm">← Kembali ke Beranda</a>
</div>

</body>

<script>
    // Contoh sederhana: Efek muncul pelan saat hasil rekomendasi keluar
    document.addEventListener("DOMContentLoaded", () => {
        const resultDiv = document.querySelector('.animate-fade-in');
        if (resultDiv) {
            resultDiv.style.opacity = 0;
            resultDiv.style.transition = "opacity 1s ease-in-out";
            setTimeout(() => { resultDiv.style.opacity = 1; }, 100);
        }
    });
</script>

</html>