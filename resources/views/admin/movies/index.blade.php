@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Header Card -->
    <div class="flex justify-between items-center mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h1 class="text-2xl font-bold text-gray-800">Manajemen Film</h1>
        <a href="{{ route('admin.movies.create') }}" 
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-semibold shadow-lg shadow-indigo-200 transition">
            + Tambah Film
        </a>
    </div>

    <!-- Tabel -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50/50">
                <tr>
                    <th class="px-8 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-wider">Judul Film</th>
                    <th class="px-8 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($movies as $movie)
                <tr class="hover:bg-gray-50 transition group">
                    <td class="px-8 py-5 text-gray-700 font-medium">{{ $movie->title }}</td>
                    <td class="px-8 py-5 text-right">
                        <form action="{{ route('admin.movies.destroy', $movie->id) }}" method="POST" onsubmit="return confirm('Hapus film ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 font-semibold px-3 py-1 rounded-lg hover:bg-red-50 transition">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection