<?php

namespace App\Repositories;

use App\Models\Perangkat;
use App\Repositories\Repository;
use App\Services\LogActivityService;
use App\Traits\QueryHelper;
use Illuminate\Support\Facades\Storage;

class PerangkatRepository extends Repository
{

    public function __construct()
    {
       parent::__construct();   
    }

    public function getDevices($search, $perPage, $sortField = null, $sortDirection = null, $condition = [])
    {
        if (empty($condition)) throw new \Exception('Tidak ada kondisi yang dipilih');

        // Ambil data perangkat dengan filter dan sorting
        return Perangkat::whereIn('kondisi', $condition)
            ->where(function ($query) use ($search) {
                $query->where('no_seri', 'like', "%{$search}%")
                    ->orWhere('kondisi', 'like', "%{$search}%");
            })

            ->orderBy($sortField ?? 'id', $sortDirection ?? 'asc')
            ->paginate($perPage);
    }

    public function createDevice(array $data)
    {
        $devices = Perangkat::create($data);
        $this->logActivityService->logActivity(
            $devices,
            'create',
            [
                $devices->no_seri => $devices->no_seri,
            ],
            'no_seri'
        );
        return $devices;
    }



    public function updateDevice($device_id, array $data)
    {
        $devices = Perangkat::find($device_id);

        if (!$devices) {
            return false;
        }
        $devices->update($data);
        $this->logActivityService->logActivity(
            $devices,
            'update',
            [
                $devices->no_seri => $devices->no_seri,
            ],
            'no_seri'
        );


        return $devices;
        // Update perangkat dengan data baru

    }


    public function updateConditions($devicesId)
    {
        $devices = Perangkat::find($devicesId);

        if (!$devices) {
            return false;
        }


        $devices->kondisi = $this->condition($devices->kondisi);
        $this->logActivityService->logActivity(
            $devices,
            'updated',
            [
                $devices->no_seri => $devices->no_seri,
            ],
            'no_seri'
        );
        $devices->save();

        return $devices;
    }

    private function condition($currentCondition)
    {
        // Toggle status between 'Aktif' and 'Tidak Aktif'
        $currentCondition = $currentCondition === 'Baik' ? 'Rusak' : 'Baik';

        return $currentCondition;
    }


    public function deleteDevice($id)
    {
        $devices = Perangkat::findOrFail($id);
        $this->logActivityService->logActivity(
            $devices,
            'delete',
            [

                $devices['no_seri'] => $devices->no_seri,
            ],
            'no_seri'
        );
        return $devices->delete();
    }

  
}
