<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogInsiden; // Pastikan Anda memiliki model LogInsiden
use App\Models\PetugasInsiden; // Untuk mendapatkan petugas_insiden_id
use App\Models\Insiden; // Untuk mendapatkan insiden_id
use Faker\Factory as Faker;
class LogInsidenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    $faker = Faker::create();

    $petugasInsiden = PetugasInsiden::all();

    foreach ($petugasInsiden as $petugas) {
        $insiden = $petugas->insiden;

        // Gunakan koordinat insiden sebagai pusat
        $baseLat = $insiden->latitude ?? -1.823446;
        $baseLng = $insiden->longitude ?? 110.182982;

        // Buat 5 log untuk setiap petugas insiden
        for ($i = 0; $i < 5; $i++) {
            LogInsiden::create([
                'insiden_id' => $insiden->id,
                'petugas_insiden_id' => $petugas->id,
                'latitude' => $baseLat + $faker->randomFloat(6, -0.002, 0.002),
                'longitude' => $baseLng + $faker->randomFloat(6, -0.002, 0.002),
                'suhu' => $faker->randomFloat(1, 27, 35),
                'kualitas_udara' => $faker->randomFloat(1, 200, 500),
                'darurat' => false // 20% kemungkinan darurat
            ]);
        }
    }
}

}
