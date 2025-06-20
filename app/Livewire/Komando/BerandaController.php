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

    protected $listeners = ['refreshMap' => 'refreshData'];

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
        $this->dispatch('refreshMap');
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
        return LogInsiden::with(['petugasInsiden.petugas', 'insiden'])
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
            'latitude' => $activeInsiden->latitude ?? $lastInsiden->latitude ?? 0,
            'longitude' => $activeInsiden->longitude ?? $lastInsiden->longitude ?? 0,
        ];
    }

    private function prepareOfficerMarkerData($logTerakhirPerPetugas)
    {
        $petugasInsidenData = [];
        foreach ($logTerakhirPerPetugas as $log) {
            if ($log->latitude && $log->longitude) {
                $petugasInsidenData[] = [
                    'latitude' => $log->latitude,
                    'longitude' => $log->longitude,
                    'nama_petugas' => $log->petugasInsiden->petugas->nama ?? 'Petugas',
                    'foto' => $log->petugasInsiden->petugas->foto ?? null,
                    'no_seri' => $log->petugasInsiden->perangkat->no_seri ?? '-',
                    'suhu' => $log->suhu ?? '-',
                    'kualitas_udara' => $log->kualitas_udara ?? '-',
                    'status_text' => $log->status ? 'Aktif' : 'Tidak Aktif',
                    'status_color' => $log->status ? 'text-success' : 'text-danger',
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
