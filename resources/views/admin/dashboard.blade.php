@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto mt-10">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, Admin!</h1>
        <p class="text-gray-600 mt-2">Anda berada di pusat kendali sistem. Silakan pilih menu di samping untuk mengelola konten.</p>
        
        <!-- Contoh Statistik Sederhana -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
            <div class="bg-indigo-50 p-6 rounded-xl border border-indigo-100">
                <p class="text-indigo-600 font-bold">Total Film</p>
                <h2 class="text-2xl font-bold text-indigo-900">12</h2> <!-- Nanti bisa diisi variabel dari controller -->
            </div>
        </div>
    </div>
</div>
@endsection