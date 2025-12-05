<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Membuat user admin pertama
        User::create([
            'name' => 'Admin Keuangan',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // password: password
            'email_verified_at' => now(),
        ]);
        
        // Anda dapat menambahkan user lain di sini jika diperlukan
        
        // Contoh membuat 10 user dummy menggunakan factory
        // User::factory(10)->create();
    }
}