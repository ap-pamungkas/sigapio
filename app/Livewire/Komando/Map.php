<?php

namespace App\Livewire\Komando;

use App\Models\Insiden;
use App\Models\LogInsiden;
use Livewire\Component;

class Map extends Component
{
    public $petugasInsidenData = [];
    public $latitude;
    public $longitude;

    protected $listeners = ['refreshMap' => 'refreshData'];

    public function mount()
    {
        $this->refreshData();
    }

    public function refreshData()
    {
        $this->petugasInsidenData = $this->getData();
        $insiden = Insiden::where('status', false)->first();
        $this->latitude = $insiden->latitude ?? -1.8179; // Default to payload latitude
        $this->longitude = $insiden->longitude ?? 110.5235; // Default to payload longitude
        $this->dispatch('petugasDataUpdated', $this->petugasInsidenData);
    }

    public function getData()
    {
        $logs = LogInsiden::with(['petugasInsiden.petugas', 'insiden'])
            ->latest('created_at')
            ->whereHas('insiden', fn($q) => $q->where('status', false))
            ->get()
            ->groupBy(function ($log) {
                return $log->petugasInsiden->petugas->id ?? null;
            })
            ->map(fn($logs) => $logs->first())
            ->filter(fn($log) => $log !== null);

        $data = [];
        foreach ($logs as $log) {
            if ($log->latitude && $log->longitude && $log->petugasInsiden && $log->petugasInsiden->petugas) {
                $data[] = [
                    'latitude' => $log->latitude,
                    'longitude' => $log->longitude,
                    'nama_petugas' => $log->petugasInsiden->petugas->nama ?? 'Petugas',
                    'foto' => $log->petugasInsiden->petugas->foto ?? null,
                    'no_seri' => $log->petugasInsiden->perangkat->no_seri ?? '-',
                    'suhu' => $log->suhu ?? '-',
                    'kualitas_udara' => $log->kualitas_udara ?? '-',
                    'status_text' => $log->status ? 'Aktif' : 'Tidak Aktif',
                    'status_color' => $log->status ? 'text-green-500' : 'text-red-500',
                ];
            }
        }
        return $data;
    }

    public function render()
    {
        return view('livewire.komando.map');
    }
}
