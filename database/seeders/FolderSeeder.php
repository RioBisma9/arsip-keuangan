<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan ID user pertama (asumsi user ini dibuat oleh DatabaseSeeder)
        // Jika tabel 'users' kosong, ini akan gagal. Pastikan UserSeeder berjalan duluan.
        $userId = DB::table('users')->first()->id ?? 1;

        // Data awal untuk folder
        $folders = [
            [
                'user_id' => $userId, // <-- WAJIB: Tentukan pemilik folder
                'name' => 'UP Bendahara',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => $userId, // <-- WAJIB: Tentukan pemilik folder
                'name' => 'SKP Kasubag Keuangan',
                'parent_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
            // Anda bisa tambahkan data folder lain di sini
        ];

        DB::table('folders')->insert($folders);
    }
}