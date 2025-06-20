<?php

namespace App\Repositories;

use App\Models\Perangkat;
use App\Repositories\Repository;
use App\Services\LogActivityService;
use App\Traits\QueryHelper;
use Illuminate\Support\Facades\Storage;

class PerangkatRepository extends Repository
{
    use QueryHelper;


    protected $logActivityService;
    public function __construct()
    {
        $this->logActivityService = new LogActivityService();
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

    public function createDevice($data)
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



    public function updateDevice($device_id, $data)
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


        $devices->kondisi = $this->conditions($devices->kondisi);
        $this->logActivityService->logActivity(
            $devices,
            'update',
            [
                $devices->no_seri => $devices->no_seri,
            ],
            'no_seri'
        );
        $devices->save();

        return $devices;
    }

    private function conditions($currentCondition)
    {
        // Toggle status between 'Aktif' and 'Tidak Aktif'
        $currentCondition = $currentCondition === 'Baik' ? 'Rusak' : 'Baik';

        return $currentCondition;
    }


    public function deleteDevices($id)
    {
        $devices = Perangkat::findOrFail($id);
        if ($devices->qr_code && Storage::disk('public')->exists($devices->qr_code)) {
            Storage::disk('public')->delete($devices->qr_code);
        }
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

    public function updateStatus($devicesId)
    {
        $devices = Perangkat::find($devicesId);

        if (!$devices) {
            return false;
        }
        // Toggle status between 'Aktif' and 'Tidak Aktif'
        $devices->status = $devices->kondisi === 'Baik' ? 'Rusak' : 'Baik';
        $devices->save();

        return $devices; // Kembalikan objek Petugas setelah diperbarui
    }
}
