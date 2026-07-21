@extends('admin.layout.app')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Daftar Film</h1>
    <a href="{{ route('admin.movies.create') }}" class="bg-red-600 hover:bg-red-700 px-4 py-2 rounded-lg font-semibold transition">+ Tambah Film</a>
</div>

@if(session('success'))
    <div class="bg-green-600 text-white p-3 rounded-lg mb-4 shadow">{{ session('success') }}</div>
@endif

<div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg border border-gray-700">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-700 text-gray-300 uppercase text-xs tracking-wider">
                <th class="p-4">Poster</th>
                <th class="p-4">Judul Film</th>
                <th class="p-4">Genre</th>
                <th class="p-4">Trailer</th>
                <th class="p-4">Popularitas</th>
                <th class="p-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-700">
            @forelse($movies as $movie)
            <tr class="hover:bg-gray-750 transition">
                <td class="p-4">
                    @if($movie->poster)
                        <img src="{{ asset('storage/' . $movie->poster) }}" alt="{{ $movie->title }}" class="w-12 h-16 object-cover rounded shadow">
                    @else
                        <span class="text-xs text-gray-500">Tidak ada</span>
                    @endif
                </td>
                <td class="p-4 font-medium">{{ $movie->title }}</td>
                <td class="p-4 text-gray-400">{{ $movie->genres }}</td>
                <td class="p-4">
                    @if($movie->trailer_url)
                        <a href="{{ $movie->trailer_url }}" target="_blank" class="text-red-400 hover:underline text-sm flex items-center">
                            ▶ Tonton
                        </a>
                    @else
                        <span class="text-xs text-gray-500">-</span>
                    @endif
                </td>
                <td class="p-4 text-gray-400">{{ $movie->popularity }}</td>
                <td class="p-4 text-center space-x-2">
                    <a href="{{ route('admin.movies.edit', $movie->id) }}" class="bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded text-sm transition">Edit</a>
                    <form action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus film ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-700 px-3 py-1.5 rounded text-sm transition">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-6 text-center text-gray-400">Belum ada data film tersedia.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $movies->links() }}
</div>
@endsection