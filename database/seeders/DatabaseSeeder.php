<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // <--- Jangan lupa import facade Hash ini

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder admin dan film secara bersamaan
        $this->call([
            AdminUserSeeder::class,
            MovieSeeder::class,
        ]);

        // Akun anggota kelompok (User)
        User::create([
            'name' => 'Muhammad Daffa Dzaki Pratama',
            'email' => 'dzaki@gmail.com',
            'password' => Hash::make('dzaki123'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Bagas Putra Baharuddin',
            'email' => 'bagas@gmail.com',
            'password' => Hash::make('bagas123'),
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Andreas Stephen Hadisuwito',
            'email' => 'andreas@gmail.com',
            'password' => Hash::make('andreas123'),
            'role' => 'user',
        ]);

        // (Opsional) Akun user bawaan Laravel untuk testing
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}