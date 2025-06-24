<?php

namespace App\Repositories;

use App\Models\Insiden;
use App\Models\LogInsiden;
use App\Models\PetugasInsiden;
use App\Repositories\Repository;
use App\Services\LogActivityService;

class PetugasInsidenRepository extends Repository
{

    
    public function __construct()
    {
       parent::__construct();   
    }
    public function getInsidenById($insidenId)
    {
        return Insiden::with(['petugasInsiden', 'petugasInsiden.petugas', 'petugasInsiden.perangkat'])->where('id', $insidenId)->first();
    }

    public function getLogByInsidenId($insidenId)
    {
        return LogInsiden::where('insiden_id', $insidenId)->get();
    }


    public function createPetugasInsiden($data)
    {
        $petugasInsiden = PetugasInsiden::create($data);
        $this->logActivityService->logActivity(
            $petugasInsiden,
            'create',
            [
                $petugasInsiden['id'] => $petugasInsiden->id,
                $petugasInsiden['insiden_id'] => $petugasInsiden->insiden_id,
                $petugasInsiden['petugas_id'] => $petugasInsiden->petugas_id,
                $petugasInsiden['perangkat_id'] => $petugasInsiden->perangkat_id,
            ],
            'insiden_id'
        );
        return $petugasInsiden;
    }


    public function getPetugasInsidenByInsiden($insiden_id, $withCoordinates = false)
    {
        // Ambil semua data terkait dengan eager loading
        $petugasInsidenList = PetugasInsiden::with([
            'petugas',
            'perangkat',
            'insidenLog' => function ($query) {
                $query->latest()->take(1); // Ambil hanya 1 log terbaru per relasi eager
            }
        ])
            ->where('insiden_id', $insiden_id)
            ->get();

        return $petugasInsidenList->map(function ($item) use ($withCoordinates) {
            $latestLog = $item->insidenLog->first(); // Sudah eager loaded

            $data = [
                'nama_petugas' => $item->petugas->nama ?? 'Tidak Diketahui',
                'foto' => $item->petugas->foto ?? null,
                'no_seri' => $item->perangkat->no_seri ?? 'Tidak Diketahui',
                'suhu' => $latestLog ? ($withCoordinates ? $latestLog->suhu : $latestLog->suhu . '°C') : '-',
                'kualitas_udara' => $latestLog ? ($withCoordinates ? $latestLog->kualitas_udara : $latestLog->kualitas_udara . ' ppm') : '-',
                'status' => $item->status ?? 'Aktif',
                'status_text' => $item->status ?? 'Aktif',
                'status_color' => $item->status === 'Tidak Aktif' ? 'text-danger' : 'text-success',
            ];

            if ($withCoordinates) {
                $data['latitude'] = $latestLog->latitude ?? null;
                $data['longitude'] = $latestLog->longitude ?? null;
            }

            return $data;
        })->all();
    }


    public function trackPetugasInsidenByInsiden($insiden_id)
    {
        return PetugasInsiden::with(['petugas', 'perangkat', 'insidenLog'])
            ->where('insiden_id', $insiden_id)
            ->get()
            ->map(function ($item) {
                $latestLog = $item->insidenLog()->latest()->first();
            
                // Skip jika tidak ada log atau koordinatnya kosong
                if (!$latestLog || !$latestLog->latitude || !$latestLog->longitude) {
                    return null; // akan difilter di bawah
                }
            
                return [
                    'nama_petugas' => $item->petugas->nama ?? 'Tidak Diketahui',
                    'foto' => $item->petugas->foto ?? null,
                    'no_seri' => $item->perangkat->no_seri ?? 'Tidak Diketahui',
                    'suhu' => $latestLog->suhu ?? '-',
                    'kualitas_udara' => $latestLog->kualitas_udara ?? '-',
                    'status' => $item->status ?? 'Aktif',
                    'status_text' => $item->status ?? 'Aktif',
                    'status_color' => $item->status === 'Tidak Aktif' ? 'text-danger' : 'text-success',
                    'latitude' => $latestLog->latitude,
                    'longitude' => $latestLog->longitude,
                ];
            })
            ->filter() // 🧹 Hapus yang null
            ->values() // 🔢 Reset index agar hasilnya array numerik
            ->all();
            
    }
}
