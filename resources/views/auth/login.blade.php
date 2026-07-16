<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .glass { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-slate-950 text-white font-sans min-h-screen flex items-center justify-center p-6">
    
    <div class="glass w-full max-w-md p-8 rounded-3xl border border-slate-800 shadow-2xl transition-all duration-500 hover:scale-[1.02]">
        <h2 class="text-3xl font-bold mb-2 text-center">Selamat Datang</h2>
        <p class="text-slate-400 text-center mb-8">Masuk untuk melanjutkan ke MovieAI</p>
        
        <form action="/login" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm mb-2 text-slate-300">Email</label>
                <input type="email" name="email" class="w-full p-4 rounded-xl bg-slate-900 border border-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
            </div>
            <div>
                <label class="block text-sm mb-2 text-slate-300">Password</label>
                <input type="password" name="password" class="w-full p-4 rounded-xl bg-slate-900 border border-slate-700 focus:ring-2 focus:ring-indigo-500 outline-none transition-all">
            </div>
            <button class="w-full bg-indigo-600 hover:bg-indigo-500 p-4 rounded-xl font-bold transition-all transform active:scale-95">Masuk Sekarang</button>
        </form>
        
        <p class="text-slate-400 text-sm mt-6 text-center">Belum punya akun? <a href="/register" class="text-indigo-400 hover:font-bold transition">Daftar di sini</a></p>
    </div>
</body>
<script>
    // Efek input focus
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.querySelector('label').classList.add('text-indigo-400');
        });
        input.addEventListener('blur', () => {
            input.parentElement.querySelector('label').classList.remove('text-indigo-400');
        });
    });
</script>
</html>