<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 flex min-h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-slate-900 text-white flex flex-col">
        <div class="p-6 text-xl font-bold border-b border-slate-700">Admin Panel</div>
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}" class="block p-3 rounded-lg hover:bg-slate-800 transition">Dashboard</a>
            <a href="{{ route('admin.movies.index') }}" class="block p-3 rounded-lg hover:bg-slate-800 transition">Manajemen Film</a>
        </nav>
        <div class="p-4 border-t border-slate-700">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full text-left p-3 text-red-400 hover:bg-slate-800 rounded-lg">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8">
        @yield('content')
    </main>
</body>
</html>