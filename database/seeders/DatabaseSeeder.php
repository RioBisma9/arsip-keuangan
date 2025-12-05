<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil UserSeeder terlebih dahulu
        // Ini memastikan tabel 'users' terisi dan 'FolderSeeder' bisa mendapatkan user_id
        $this->call([
            UserSeeder::class, // WAJIB DIJALANKAN PERTAMA
            FolderSeeder::class,
            // Jika ada FileSeeder, letakkan di sini juga setelah FolderSeeder
        ]);

        // Anda bisa tambahkan User::factory(10)->create(); di sini jika Anda ingin membuat user tambahan
    }
}