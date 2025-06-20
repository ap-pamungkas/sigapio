<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Perangkat; // Pastikan Anda memiliki model Perangkat
use Illuminate\Support\Str; // Untuk UUID

class PerangkatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Perangkat::create([

            'no_seri' => 'SN-PRK-001',
            'kondisi' => 'Baik',
        ]);

        Perangkat::create([

            'no_seri' => 'SN-PRK-002',
            'kondisi' => 'Baik',
        ]);

        Perangkat::create([

            'no_seri' => 'SN-PRK-003',
            'kondisi' => 'Rusak',
        ]);

        Perangkat::create([

            'no_seri' => 'SN-PRK-004',
            'kondisi' => 'Baik',
        ]);
    }
}
