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
    public $petugasInsidenData; // This property is now explicitly for passing data to the map component if needed, or can be removed if the map component fetches its own.

    // No need for a refreshMap listener here if the map component handles its own polling.

    public function mount()
    {
        // Fetch initial data for the dashboard summary
        $this->loadDashboardData();

        // Initialize map coordinates based on active incidents
        $this->setMapCoordinates();

        // This data is likely consumed by the embedded Livewire map component directly,
        // so it might not be strictly necessary to set it here if the map component
        // fetches its own data with wire:poll.5s.
        // However, keeping it here for initial load consistency if the map component
        // relies on this initial data.
        $this->petugasInsidenData = $this->getOfficerMarkerData();
    }

    /**
     * Refreshes the dashboard data.
     * This method could be called if a specific action on the dashboard needs to
     * trigger a refresh of the summary statistics.
     * The map component already polls separately.
     */
    public function refreshDashboard()
    {
        $this->loadDashboardData();
        $this->setMapCoordinates(); // Re-evaluate map center if incidents change
        $this->petugasInsidenData = $this->getOfficerMarkerData(); // Re-fetch officer data for map

        // If the map component relies on a Livewire event for initial data or subsequent updates
        // beyond its own polling, you could dispatch here. However, with wire:poll.5s,
        // this dispatch is often not needed unless you want immediate updates outside the poll interval.
        // $this->dispatch('petugasDataUpdated', $this->petugasInsidenData); // Example dispatch for map component
    }

    /**
     * Loads the summary data for active incidents, completed incidents, and officer logs.
     */
    private function loadDashboardData()
    {
        $this->insidenAktif = Insiden::withCount('petugasInsiden')
            ->where('status', false)
            ->latest()
            ->get();

        $this->insidenSelesai = Insiden::where('status', true)
            ->latest()
            ->take(5)
            ->get();

        $this->logPetugas = LogInsiden::with(['petugasInsiden.petugas', 'insiden'])
            ->latest('created_at')
            ->whereHas('insiden', function ($query) {
                $query->where('status', false);
            })
            ->get()
            ->groupBy('petugas_insiden_id')
            ->map(fn($logs) => $logs->first());
    }

    /**
     * Sets the initial latitude and longitude for the map.
     * Prioritizes the latest active incident, then the latest overall incident,
     * otherwise defaults to a sensible location (Jakarta).
     */
    private function setMapCoordinates()
    {
        $activeInsiden = Insiden::where('status', false)->latest()->first();
        $lastInsiden = Insiden::latest('created_at')->first();

        // Default to Jakarta if no incidents found
        $this->latitude = $activeInsiden->latitude ?? $lastInsiden->latitude ?? -6.2; // Default Jakarta Latitude
        $this->longitude = $activeInsiden->longitude ?? $lastInsiden->longitude ?? 106.8; // Default Jakarta Longitude
    }

    /**
     * Prepares officer data for map markers.
     * This method fetches and formats the latest log data for officers.
     * It's good practice to encapsulate this logic.
     */
    private function getOfficerMarkerData()
    {
        $logs = LogInsiden::with(['petugasInsiden.petugas', 'insiden'])
            ->latest('created_at')
            ->whereHas('insiden', fn($q) => $q->where('status', false))
            ->get()
            ->groupBy('petugas_insiden_id')
            ->map(fn($logs) => $logs->first());

        $petugasInsidenData = [];
        foreach ($logs as $log) {
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
