<div class="px-12 py-6 group relative">
    <h3 class="text-2xl font-bold mb-4">{{ $title }}</h3>
    
    <button onclick="scrollSlider('{{ $id }}', -800)" class="absolute left-2 top-[55%] z-40 bg-black/50 p-3 rounded-full opacity-0 group-hover:opacity-100 transition">❮</button>
    
    <div id="{{ $id }}" class="grid grid-flow-col auto-cols-[180px] gap-6 overflow-x-auto scrollbar-hide scroll-smooth pb-4">
        @foreach($movies as $movie)
        <a href="{{ url('/movie/detail', $movie->id) }}" class="group/card relative aspect-[2/3] rounded-lg overflow-hidden cursor-pointer shadow-lg hover:ring-4 hover:ring-red-600 transition duration-300">
            
            <!-- LOGIKA POSTER -->
            @if(isset($movie->poster_url) && $movie->poster_url)
                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-slate-800 flex items-center justify-center p-4 text-center">
                    <span class="font-bold text-sm">{{ $movie->title }}</span>
                </div>
            @endif

            <!-- Overlay Judul Saat Hover -->
            <div class="absolute inset-0 bg-black/80 opacity-0 group-hover/card:opacity-100 transition p-4 flex flex-col justify-end">
                <h4 class="font-bold text-sm">{{ $movie->title }}</h4>
            </div>
        </a>
        @endforeach
    </div>
    
    <button onclick="scrollSlider('{{ $id }}', 800)" class="absolute right-2 top-[55%] z-40 bg-black/50 p-3 rounded-full opacity-0 group-hover:opacity-100 transition">❯</button>
</div>