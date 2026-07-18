<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Genre - MovieAI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass { background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-slate-950 text-white font-sans antialiased min-h-screen">

    <nav class="sticky top-0 z-50 glass border-b border-slate-800">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-indigo-400 tracking-tight">MovieAI</h2>
            
            <div class="flex gap-8 items-center font-medium text-slate-300">
                <a href="/dashboard" class="hover:text-white transition">Home</a>
                <a href="/genre" class="text-white border-b-2 border-red-600 transition">Genre</a>
                <a href="/recommend" class="hover:text-white transition">Rekomendasi AI</a>
                <a href="/my-list" class="hover:text-white transition">My List</a>
            </div>

            <div class="flex gap-4 items-center relative group">
                <button class="flex items-center gap-2 hover:text-red-500 transition font-bold focus:outline-none">
                    {{ auth()->user()->name }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <!-- Dropdown Menu -->
                <div class="absolute top-full right-0 mt-2 w-48 bg-slate-900 border border-slate-700 rounded-lg shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">
                    <div class="py-2">
                        <button onclick="document.getElementById('profileModal').classList.remove('hidden')" class="w-full text-left px-4 py-2 text-sm hover:bg-slate-800 transition">Edit Profile</button>
                        <form action="/logout" method="POST" class="m-0">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-slate-800 transition">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-12">
        <h1 class="text-4xl font-bold mb-8 text-center bg-clip-text text-transparent bg-gradient-to-r from-indigo-400 to-cyan-400">Jelajahi Berdasarkan Genre</h1>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($genres as $genre)
            <a href="{{ route('genre.show', $genre) }}" class="group relative h-40 rounded-xl overflow-hidden cursor-pointer shadow-lg hover:ring-4 hover:ring-indigo-500 transition duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 to-slate-900 opacity-80 group-hover:opacity-100 transition duration-300"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <h3 class="text-2xl font-bold text-white group-hover:scale-110 transition duration-300">{{ $genre }}</h3>
                </div>
            </a>
            @endforeach
        </div>
    </main>

    <!-- Floating Toast Notifications -->
    @if(session('success'))
    <div id="toast-success" class="fixed top-24 left-1/2 -translate-x-1/2 z-[200] bg-green-500/90 text-white px-6 py-3 rounded-lg shadow-2xl flex items-center gap-3 transition-opacity duration-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span class="font-bold">{{ session('success') }}</span>
    </div>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-success');
            if (toast) {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 500);
            }
        }, 3000);
    </script>
    @endif

    @if($errors->any())
    <div id="toast-error" class="fixed top-24 left-1/2 -translate-x-1/2 z-[200] bg-red-500/90 text-white px-6 py-3 rounded-lg shadow-2xl flex items-center gap-3 transition-opacity duration-500">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        <span class="font-bold">{{ $errors->first() }}</span>
    </div>
    <script>
        // Buka modal otomatis jika ada error validasi
        document.addEventListener("DOMContentLoaded", () => {
            document.getElementById('profileModal').classList.remove('hidden');
        });
        setTimeout(() => {
            const toast = document.getElementById('toast-error');
            if (toast) {
                toast.classList.add('opacity-0');
                setTimeout(() => toast.remove(), 500);
            }
        }, 4000);
    </script>
    @endif

    <!-- Profile Edit Modal -->
    <div id="profileModal" class="fixed inset-0 z-[100] hidden">
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="document.getElementById('profileModal').classList.add('hidden')"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-full max-w-md">
            <div class="bg-slate-900 border border-slate-700 rounded-2xl shadow-2xl p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-white">Edit Profile</h3>
                    <button onclick="document.getElementById('profileModal').classList.add('hidden')" class="text-slate-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Email</label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}" required class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-1">Password Baru (Opsional)</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak ingin ganti" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none transition">
                    </div>
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg mt-4 transition shadow-lg shadow-red-900/20">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
