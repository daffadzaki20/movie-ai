<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;
use Illuminate\Support\Facades\DB;

class MovieSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Nonaktifkan foreign key checks sementara & kosongkan tabel movies
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('movies')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

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
            // Ekstrak dan parsing JSON genres menjadi teks (contoh: "Action, Comedy")
            $genresString = '';
            if (isset($data[1]) && !empty($data[1])) {
                $genresArray = json_decode($data[1], true);
                if (is_array($genresArray)) {
                    $genreNames = array_column($genresArray, 'name');
                    $genresString = implode(', ', $genreNames);
                }
            }

            Movie::create([
                // Sesuaikan indeks kolom CSV TMDB 5000
                'movie_id'   => isset($data[3]) && is_numeric($data[3]) ? $data[3] : rand(1000, 99999),
                'title'      => $data[17] ?? ($data[19] ?? 'Unknown Movie'),
                'overview'   => $data[6] ?? 'No overview available.',
                'genres'     => $genresString,
                'popularity' => isset($data[8]) && is_numeric($data[8]) ? (float) $data[8] : 0,
            ]);
        }

        fclose($file);
        $this->command->info("Selamat! Ribuan data film berhasil ditransfer ke MySQL!");
    }
}