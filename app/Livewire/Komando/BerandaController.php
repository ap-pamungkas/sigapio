<?php

namespace App\Livewire\Komando;

use App\Models\Insiden;
use App\Models\LogInsiden;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class BerandaController extends Component
{
    #[Title("Komando")]
    #[Layout("components.layouts.komando")]

    public $insidenAktif;
    public $insidenSelesai;
    public $logPetugas;
    public $latitude;
    public $longitude;
    public $petugasInsidenData;

    public function mount()
    {
        $this->insidenAktif = $this->getActiveIncidents();
        $this->insidenSelesai = $this->getCompletedIncidents();
        $this->logPetugas = $this->getLatestOfficerLogs();
        $coordinates = $this->getMapCoordinates();
        $this->latitude = $coordinates['latitude'];
        $this->longitude = $coordinates['longitude'];
        $this->petugasInsidenData = $this->prepareOfficerMarkerData($this->logPetugas);
    }

    public function refreshData()
    {
        // Refresh all data
        $this->insidenAktif = $this->getActiveIncidents();
        $this->logPetugas = $this->getLatestOfficerLogs();
        $this->petugasInsidenData = $this->prepareOfficerMarkerData($this->logPetugas);
        
        // Pass the updated data to the Map component
        $this->dispatch('refreshMapData', [
            'petugasData' => $this->petugasInsidenData,
            'timestamp' => now()->timestamp
        ]);
    }

    private function getActiveIncidents()
    {
        return Insiden::withCount('petugasInsiden')
            ->where('status', false)
            ->latest()
            ->get();
    }

    private function getCompletedIncidents()
    {
        return Insiden::where('status', true)
            ->latest()
            ->take(5)
            ->get();
    }

    private function getLatestOfficerLogs()
    {
        return LogInsiden::with(['petugasInsiden.petugas', 'petugasInsiden.perangkat', 'insiden'])
            ->latest('created_at')
            ->whereHas('insiden', function($query) {
                $query->where('status', false);
            })
            ->get()
            ->groupBy('petugas_insiden_id')
            ->map(fn($logs) => $logs->first());
    }

    private function getMapCoordinates()
    {
        $activeInsiden = Insiden::where('status', false)
            ->latest()
            ->first();

        $lastInsiden = Insiden::latest('created_at')
            ->first();

        return [
            'latitude' => $activeInsiden->latitude ?? $lastInsiden->latitude ?? -0.0263,
            'longitude' => $activeInsiden->longitude ?? $lastInsiden->longitude ?? 109.3425,
        ];
    }

    private function prepareOfficerMarkerData($logTerakhirPerPetugas)
    {
        $petugasInsidenData = [];
        
        foreach ($logTerakhirPerPetugas as $log) {
            // Add validation for required data
            if ($log->latitude && $log->longitude && $log->petugasInsiden && $log->petugasInsiden->petugas) {
                $petugasInsidenData[] = [
                    'latitude' => (float) $log->latitude,
                    'longitude' => (float) $log->longitude,
                    'nama_petugas' => $log->petugasInsiden->petugas->nama ?? 'Petugas Tidak Diketahui',
                    'foto' => $log->petugasInsiden->petugas->foto ?? null,
                    'no_seri' => $log->petugasInsiden->perangkat->no_seri ?? 'N/A',
                    'suhu' => $log->suhu ?? 'N/A',
                    'kualitas_udara' => $log->kualitas_udara ?? 'N/A',
                    'status_text' => $log->status ? 'Aktif' : 'Tidak Aktif',
                    'status_color' => $log->status ? 'text-success' : 'text-danger',
                    'petugas_insiden_id' => $log->petugas_insiden_id,
                    'created_at' => $log->created_at->format('Y-m-d H:i:s')
                ];
            }
        }
        
        return $petugasInsidenData;
    }

    public function render()
    {
        return view('livewire.komando.beranda');
    }
}