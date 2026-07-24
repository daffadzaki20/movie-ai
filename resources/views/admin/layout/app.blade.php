<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MovieAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex h-screen overflow-hidden">

    <!-- SIDEBAR / NAVBAR SAMPING -->
    <div class="w-64 bg-gray-800 border-r border-gray-700 flex flex-col justify-between">
        <div>
            <!-- Logo / Brand -->
            <div class="p-5 text-2xl font-bold tracking-wider text-red-500 border-b border-gray-700">
                Movie<span class="text-white">Admin</span>
            </div>

            <!-- Menu Navigasi -->
            <nav class="mt-5 px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }} transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Kelola Film
                </a>
                <a href="{{ route('admin.movies.create') }}" class="flex items-center px-4 py-2.5 rounded-lg {{ request()->routeIs('admin.movies.create') ? 'bg-red-600 text-white' : 'text-gray-400 hover:bg-gray-700 hover:text-white' }} transition">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Tambah Film Baru
                </a>
            </nav>
        </div>

        <!-- Tombol Logout di Bawah -->
        <div class="p-4 border-t border-gray-700">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-lg font-semibold transition">
                    Keluar (Logout)
                </button>
            </form>
        </div>
    </div>

    <!-- KONTEN UTAMA -->
    <div class="flex-1 flex flex-col overflow-y-auto">
        <!-- Topbar Atas -->
        <header class="bg-gray-800 border-b border-gray-700 px-8 py-4 flex justify-between items-center shadow-md">
            <h2 class="text-xl font-semibold text-gray-200">Panel Kontrol Administrator</h2>
            <div class="flex items-center space-x-3">
                <span class="text-sm text-gray-400">Halo, <strong class="text-white">{{ Auth::user()->name ?? 'Admin' }}</strong></span>
            </div>
        </header>

        <!-- Bagian Isi Halaman Dinamis -->
        <main class="p-8">
            @yield('content')
        </main>
    </div>

</body>
</html>