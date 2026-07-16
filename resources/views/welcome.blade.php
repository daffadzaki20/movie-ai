<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .fade-in-section {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }
        .fade-in-section.is-visible {
            opacity: 1;
            transform: translateY(0);
        }
        .glass { background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="bg-slate-950 text-white font-sans antialiased">

    <!-- Navbar -->
    <nav class="sticky top-0 z-50 glass border-b border-slate-800">
        <div class="max-w-6xl mx-auto px-6 py-4 flex justify-between items-center">
            <h2 class="text-2xl font-bold text-indigo-400 tracking-tight">MovieAI</h2>
            
            <!-- Navbar Items (Sudah diperbaiki dengan items-center agar sejajar) -->
            <div class="flex gap-6 items-center">
                <a href="/login" class="text-slate-300 hover:text-white transition">Masuk</a>
                <a href="/register" class="bg-indigo-600 hover:bg-indigo-500 px-5 py-2 rounded-lg text-sm font-bold transition shadow-lg shadow-indigo-900/20">Daftar Gratis</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="fade-in-section relative py-24 px-6 text-center overflow-hidden">
        <div class="absolute inset-0 bg-indigo-900/20 blur-[120px] rounded-full"></div>
        <h1 class="relative text-5xl md:text-7xl font-extrabold mb-6 leading-tight">
            Temukan Film Impianmu <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Dalam Hitungan Detik</span>
        </h1>
        <p class="relative text-slate-400 text-xl max-w-2xl mx-auto mb-10">
            MovieAI tidak hanya memberikan daftar film, tapi memahami preferensi personalmu untuk memberikan rekomendasi yang benar-benar berkualitas.
        </p>
        <a href="/register" class="relative inline-flex items-center gap-2 bg-white text-slate-900 px-8 py-4 rounded-full font-bold hover:scale-105 transition duration-300 shadow-xl">
            Mulai Eksplorasi Sekarang <i class="fas fa-arrow-right"></i>
        </a>
    </header>

    <!-- How It Works Section -->
    <section class="fade-in-section max-w-6xl mx-auto py-20 px-6">
        <h2 class="text-3xl font-bold text-center mb-16">Bagaimana MovieAI Bekerja?</h2>
        <div class="grid md:grid-cols-3 gap-12">
            <div class="card p-6 rounded-3xl border border-slate-800 hover:border-indigo-500 transition-all duration-300 bg-slate-900/50">
                <div class="w-16 h-16 bg-indigo-600/20 text-indigo-400 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">1</div>
                <h3 class="text-xl font-bold mb-3">Buat Akun</h3>
                <p class="text-slate-400">Daftar dalam 30 detik untuk mulai mempersonalisasi pengalaman menontonmu.</p>
            </div>
            <div class="card p-6 rounded-3xl border border-slate-800 hover:border-indigo-500 transition-all duration-300 bg-slate-900/50">
                <div class="w-16 h-16 bg-cyan-600/20 text-cyan-400 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">2</div>
                <h3 class="text-xl font-bold mb-3">Analisis AI</h3>
                <p class="text-slate-400">AI kami memproses ribuan data film untuk mencocokkan selera tontonanmu secara instan.</p>
            </div>
            <div class="card p-6 rounded-3xl border border-slate-800 hover:border-indigo-500 transition-all duration-300 bg-slate-900/50">
                <div class="w-16 h-16 bg-purple-600/20 text-purple-400 rounded-2xl flex items-center justify-center text-2xl mx-auto mb-6">3</div>
                <h3 class="text-xl font-bold mb-3">Tonton & Nikmati</h3>
                <p class="text-slate-400">Dapatkan rekomendasi judul film yang tidak akan membuatmu menyesal menghabiskan waktu.</p>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="fade-in-section bg-slate-900/50 py-16 border-y border-slate-800">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <div><div class="text-4xl font-bold text-indigo-400">10K+</div><div class="text-slate-400 text-sm mt-2">Film Terindeks</div></div>
            <div><div class="text-4xl font-bold text-cyan-400">98%</div><div class="text-slate-400 text-sm mt-2">Tingkat Akurasi</div></div>
            <div><div class="text-4xl font-bold text-purple-400">5K+</div><div class="text-slate-400 text-sm mt-2">Pengguna Aktif</div></div>
            <div><div class="text-4xl font-bold text-white">24/7</div><div class="text-slate-400 text-sm mt-2">Siap Melayani</div></div>
        </div>
    </section>

    <script>
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) entry.target.classList.add('is-visible');
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-in-section').forEach(el => observer.observe(el));

        document.querySelectorAll('.card').forEach(card => {
            card.addEventListener('mouseenter', () => card.classList.add('scale-105'));
            card.addEventListener('mouseleave', () => card.classList.remove('scale-105'));
        });
    </script>
</body>
</html>