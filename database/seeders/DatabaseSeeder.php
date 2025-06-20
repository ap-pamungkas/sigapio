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
        // Urutan penting karena ada foreign key
        $this->call([

            PetugasSeeder::class,
            PerangkatSeeder::class,
            InsidenSeeder::class,
            PetugasInsidenSeeder::class, // Tergantung pada Insiden, Petugas, Perangkat
            LogInsidenSeeder::class,     // Tergantung pada Insiden, PetugasInsiden
            UserSeeder::class,
        ]);
    }
}
