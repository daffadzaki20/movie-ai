<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Kosongkan tabel movies terlebih dahulu agar bersih
        DB::table('movies')->truncate();

        // 2. Tentukan lokasi file CSV film kamu di folder python (ai)
        $csvFile = base_path('AI/dataset/tmdb_5000_movies.csv');

        // Cek apakah filenya ada
        if (!file_exists($csvFile)) {
            $this->command->error("File CSV tidak ditemukan di: $csvFile. Pastikan nama folder dan filenya benar!");
            return;
        }

        // 3. Baca file CSV
        $file = fopen($csvFile, 'r');
        $header = fgetcsv($file); // Lewati baris pertama (header)

        $this->command->info("Sedang memasukkan data film ke MySQL, mohon tunggu...");

        // Looping untuk memasukkan data baris demi baris
        while (($data = fgetcsv($file)) !== FALSE) {
            Movie::create([
                // Sesuaikan indeks kolom CSV TMDB 5000 (3 = id, 17 = title, 6 = overview)
                'movie_id' => isset($data[3]) && is_numeric($data[3]) ? $data[3] : rand(1000, 99999),
                'title'    => $data[17] ?? ($data[19] ?? 'Unknown Movie'),
                'overview' => $data[6] ?? 'No overview available.',
            ]);
        }

        fclose($file);
        $this->command->info("Selamat! Ribuan data film berhasil ditransfer ke MySQL!");
    }
}