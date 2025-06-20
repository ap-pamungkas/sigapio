<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PetugasInsiden;
use App\Models\Insiden;
use App\Models\Petugas;
use App\Models\Perangkat; // Penting: Pastikan model Perangkat diimport

class PetugasInsidenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil data yang sudah ada dari seeder sebelumnya
        $insiden1 = Insiden::find(1);
        $insiden2 = Insiden::find(2);

        $petugas1 = Petugas::find(2); // Siti Aminah (Teknisi Lapangan)
        $petugas2 = Petugas::find(3); // Joko Susanto (Teknisi Lapangan)

        // *** PENTING: Ambil ID perangkat yang benar-benar ada di database ***
        $perangkat1 = Perangkat::first(); // Mengambil perangkat pertama yang ada
        $perangkat2 = Perangkat::skip(1)->first(); // Mengambil perangkat kedua yang ada

        // Pastikan semua data yang dibutuhkan ada sebelum membuat relasi
        if ($insiden1 && $petugas1 && $perangkat1) {
            PetugasInsiden::create([
                'insiden_id' => $insiden1->id,
                'petugas_id' => $petugas1->id,
                'perangkat_id' => $perangkat1->id, // Menggunakan ID UUID dari perangkat yang sudah ada
                'status' => 'Aktif',
            ]);
        } else {
            $this->command->warn('Skipping PetugasInsidenSeeder entry for Insiden 1, Petugas 1. One or more dependencies not found.');
        }


        if ($insiden1 && $petugas2 && $perangkat2) {
            PetugasInsiden::create([
                'insiden_id' => $insiden1->id,
                'petugas_id' => $petugas2->id,
                'perangkat_id' => $perangkat2->id, // Menggunakan ID UUID dari perangkat yang sudah ada
                'status' => 'Aktif',
            ]);
        } else {
            $this->command->warn('Skipping PetugasInsidenSeeder entry for Insiden 1, Petugas 2. One or more dependencies not found.');
        }

        if ($insiden2 && $petugas1 && $perangkat1) {
            PetugasInsiden::create([
                'insiden_id' => $insiden2->id,
                'petugas_id' => $petugas1->id,
                'perangkat_id' => $perangkat1->id, // Menggunakan ID UUID dari perangkat yang sudah ada
                'status' => 'Aktif',
            ]);
        } else {
             $this->command->warn('Skipping PetugasInsidenSeeder entry for Insiden 2, Petugas 1. One or more dependencies not found.');
        }
    }
}
