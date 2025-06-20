<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Insiden; // Pastikan Anda memiliki model Insiden

class InsidenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Insiden::create([
            'nama_insiden' => 'Kebakaran Lahan',
            'keterangan' => 'Terjadi kebakaran di lahan kosong dekat pemukiman warga.',
            'latitude' => -6.208763,
            'longitude' => 106.845599,
            'status' => true,
        ]);

        Insiden::create([
            'nama_insiden' => 'Banjir Lokal',
            'keterangan' => 'Genangan air tinggi di area perumahan akibat hujan deras.',
            'latitude' => -6.175392,
            'longitude' => 106.827189,
            'status' => true,
        ]);

        Insiden::create([
            'nama_insiden' => 'Pohon Tumbang',
            'keterangan' => 'Pohon besar tumbang menimpa jalan dan menghalangi lalu lintas.',
            'latitude' => -6.229728,
            'longitude' => 106.689431,
            'status' => true,
        ]);

        Insiden::create([
            'nama_insiden' => 'Kecelakaan Lalu Lintas',
            'keterangan' => 'Kecelakaan tunggal mobil di jalan tol.',
            'latitude' => -6.300000,
            'longitude' => 106.700000,
            'status'=> true,
        ]);
    }
}
