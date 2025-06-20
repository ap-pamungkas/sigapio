<?php

namespace App\Repositories;

use App\Models\Insiden;
use App\Models\LogInsiden;
use App\Models\PetugasInsiden;
use App\Repositories\Repository;
use App\Services\LogActivityService;

class PetugasInsidenRepository extends Repository
{

    protected $logActivityService;

    public function __construct()
    {
        $this->logActivityService =  new LogActivityService();
    }
    public function getInsidenById($insidenId)
    {
       return Insiden::with(['petugasInsiden','petugasInsiden.petugas','petugasInsiden.perangkat'])->where('id', $insidenId)->first();
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


public function getPetugasInsidenByInsiden($insiden_id)
    {
        return PetugasInsiden::with(['petugas', 'perangkat', 'insidenLog'])
            ->where('insiden_id', $insiden_id)
            ->get()
            ->map(function ($item) {
                $latestLog = $item->insidenLog()->latest()->first();
                return [
                    'nama_petugas' => $item->petugas->nama ?? 'Tidak Diketahui',
                    'foto' => $item->petugas->foto ?? null,
                    'no_seri' => $item->perangkat->no_seri ?? 'Tidak Diketahui',
                    'suhu' => $latestLog ? $latestLog->suhu . '°C' : '-',
                    'kualitas_udara' => $latestLog ? $latestLog->kualitas_udara  .' ppm' : '-',
                    'status' => $item->status ?? 'Aktif',
                    'status_text' => $item->status ?? 'Aktif',
                    'status_color' => $item->status === 'Tidak Aktif' ? 'text-danger' : 'text-success',

                ];
            })->all();;


    }

    public function trackPetugasInsidenByInsiden($insiden_id)
    {
        return PetugasInsiden::with(['petugas', 'perangkat', 'insidenLog'])
            ->where('insiden_id', $insiden_id)
            ->get()
            ->map(function ($item) {
                $latestLog = $item->insidenLog()->latest()->first();
                return [
                    'nama_petugas' => $item->petugas->nama ?? 'Tidak Diketahui',
                    'foto' => $item->petugas->foto ?? null,
                    'no_seri' => $item->perangkat->no_seri ?? 'Tidak Diketahui',
                    'suhu' => $latestLog ? $latestLog->suhu : '-',
                    'kualitas_udara' => $latestLog ? $latestLog->kualitas_udara : '-',
                    'status' => $item->status ?? 'Aktif',
                    'status_text' => $item->status ?? 'Aktif',
                    'status_color' => $item->status === 'Tidak Aktif' ? 'text-danger' : 'text-success',
                    'latitude' => $latestLog ? $latestLog->latitude : null,
                    'longitude' => $latestLog ? $latestLog->longitude : null,
                ];
            })->all();;


    }

}



