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
        $csvFile = base_path('AI/dataset/movies_metadata.csv');

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
            // Pastikan baris CSV valid dan memiliki jumlah kolom yang cukup
            if (!isset($data[18])) {
                continue;
            }

            // 1. Ekstrak dan parsing JSON genres (berada di indeks 3)
            $genresString = '';
            if (isset($data[3]) && !empty($data[3])) {
                // Bersihkan format petik tunggal jika ada pada string JSON dari CSV
                $jsonClean = str_replace("'", '"', $data[3]);
                $genresArray = json_decode($jsonClean, true);
                if (is_array($genresArray)) {
                    $genreNames = array_column($genresArray, 'name');
                    $genresString = implode(', ', $genreNames);
                }
            }

            // 2. Ambil ID film (berada di indeks 5)
            $movieId = isset($data[5]) && is_numeric($data[5]) ? $data[5] : rand(1000, 99999);

            // 3. Gunakan updateOrCreate untuk mencegah error duplicate entry dengan indeks kolom yang benar
            Movie::updateOrCreate(
                ['movie_id' => $movieId], // Kunci pencarian unik
                [
                    'title'      => !empty($data[8]) ? $data[8] : (!empty($data[18]) ? $data[18] : 'Unknown Movie'), // Judul film
                    'overview'   => !empty($data[9]) ? $data[9] : 'No overview available.', // Sinopsis film
                    'genres'     => $genresString,
                    'popularity' => isset($data[10]) && is_numeric($data[10]) ? (float) $data[10] : 0, // Tingkat popularitas
                ]
            );
        }

        fclose($file);
        $this->command->info("Selamat! Ribuan data film berhasil ditransfer ke MySQL!");
    }
}