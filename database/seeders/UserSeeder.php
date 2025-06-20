<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; // Pastikan Anda memiliki model User
use Illuminate\Support\Facades\Hash; // Untuk hashing password

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nama' => 'Admin Utama',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'role'=> 1,
            'password' => Hash::make('password'), // Ganti dengan password yang kuat

        ]);

        User::create([
            'nama' => 'User Biasa',
            'username' => 'user',
            'email' => 'user@example.com',
            'email_verified_at' => now(),
            'role'=> 0,
            'password' => Hash::make('password'), // Ganti dengan password yang kuat
        ]);
    }
}
