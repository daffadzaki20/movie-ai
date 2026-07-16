<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .navbar-blur { background: rgba(0, 0, 0, 0.8); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-slate-950 text-white font-sans">
    
    <!-- Navbar -->
    <nav id="navbar" class="fixed w-full z-50 p-6 flex justify-between items-center transition-all duration-300">
        <h2 class="text-3xl font-bold text-red-600 tracking-tighter cursor-pointer">MOVIEAI</h2>
        <div class="flex gap-8 items-center font-medium text-slate-300">
            <a href="/dashboard" class="text-white border-b-2 border-red-600">Home</a>
            <a href="/genre" class="hover:text-white transition">Genre</a>
            <a href="/recommend" class="hover:text-white transition">Rekomendasi AI</a>
            <a href="/my-list" class="hover:text-white transition">My List</a>
        </div>
        <div class="flex gap-4 items-center">
            <a href="/profile" class="hover:text-red-500 transition font-bold">Profile</a>
            <form action="/logout" method="POST">
                @csrf
                <button class="bg-red-600 px-5 py-2 rounded-lg hover:bg-red-700 transition font-bold text-white">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative w-full h-[70vh] flex items-center px-12 bg-slate-900 overflow-hidden">
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

    <script>
        const navbar = document.getElementById('navbar');
        window.onscroll = () => {
            if (window.scrollY > 50) navbar.classList.add('navbar-blur');
            else navbar.classList.remove('navbar-blur');
        };

        function scrollSlider(id, offset) {
            document.getElementById(id).scrollBy({ left: offset, behavior: 'smooth' });
        }
    </script>
</body>
</html>