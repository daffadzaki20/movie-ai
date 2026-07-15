<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white font-sans">
    <nav class="p-6 flex justify-between items-center max-w-6xl mx-auto">
        <h2 class="text-2xl font-bold text-indigo-400">MovieAI</h2>
        <div class="flex gap-6 items-center">
            <a href="/dashboard" class="hover:text-indigo-400">Home</a>
            <a href="/recommend" class="hover:text-indigo-400 font-bold">Rekomendasi Film</a>
            <form action="/logout" method="POST">
                @csrf
                <button class="text-red-400">Logout</button>
            </form>
        </div>
    </nav>
    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-4xl font-bold">Halo, {{ auth()->user()->name }}!</h1>
        <p class="text-slate-400 mt-4">Selamat datang di MovieAI. Pilih menu "Rekomendasi Film" di atas untuk mulai mencari film favoritmu.</p>
    </div>
</body>
</html>