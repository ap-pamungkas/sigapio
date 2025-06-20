<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Petugas; // Pastikan Anda memiliki model Petugas
use App\Models\Jabatan; // Untuk mendapatkan jabatan_id

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Petugas::create([
            'nama' => 'Budi Santoso',
            'alamat' => 'Jl. Merdeka No. 10, Jakarta',

            'status' => 'Aktif',
            'jenis_kelamin' => 'Laki-laki',
            'tgl_lahir' => '1985-05-15',
            'foto' => null,
        ]);

        Petugas::create([
            'nama' => 'Siti Aminah',
            'alamat' => 'Jl. Kenanga No. 5, Bandung',

            'status' => 'Aktif',
            'jenis_kelamin' => 'Perempuan',
            'tgl_lahir' => '1990-11-22',
            'foto' => null,
        ]);

        Petugas::create([
            'nama' => 'Joko Susanto',
            'alamat' => 'Jl. Pahlawan No. 20, Surabaya',

            'status' => 'Aktif',
            'jenis_kelamin' => 'Laki-laki',
            'tgl_lahir' => '1992-03-01',
            'foto' => null,
        ]);

        Petugas::create([
            'nama' => 'Dewi Lestari',
            'alamat' => 'Jl. Mawar No. 12, Yogyakarta',

            'status' => 'Aktif',
            'jenis_kelamin' => 'Perempuan',
            'tgl_lahir' => '1995-07-30',
            'foto' => null,
        ]);

        Petugas::create([
            'nama' => 'Rina Wijaya',
            'alamat' => 'Jl. Anggrek No. 8, Semarang',

            'status' => 'Aktif',
            'jenis_kelamin' => 'Perempuan',
            'tgl_lahir' => '1988-09-10',
            'foto' => null,
        ]);
    }
}
