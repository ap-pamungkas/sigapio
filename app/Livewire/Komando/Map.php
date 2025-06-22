<?php
// App/Livewire/Komando/Map.php

namespace App\Livewire\Komando;

use App\Models\Insiden;
use App\Repositories\PetugasInsidenRepository;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\Attributes\On;

class Map extends Component
{
    public $insiden;
    public $insiden_id;
    public $latitude;
    public $longitude;
    public $petugasInsidenData = [];
    public $lastUpdated;

    protected PetugasInsidenRepository $petugasInsidenRepository;

    public function boot(PetugasInsidenRepository $petugasInsidenRepository)
    {
        $this->petugasInsidenRepository = $petugasInsidenRepository;
    }

    public function mount()
    {
        $this->insiden = Insiden::where('status', false)->first();

        if ($this->insiden) {
            $this->insiden_id = $this->insiden->id;
            $this->latitude = $this->insiden->latitude;
            $this->longitude = $this->insiden->longitude;
            $this->loadPetugasInsidenData();
        } else {
            // Fallback lokasi default jika tidak ada insiden aktif
            $this->latitude = -0.0263;   // Pontianak coordinates
            $this->longitude = 109.3425;
            $this->petugasInsidenData = [];
        }
        
        $this->lastUpdated = now()->timestamp;
    }

    public function loadPetugasInsidenData()
    {
        if (!$this->insiden_id) {
            $this->petugasInsidenData = [];
            return;
        }

        try {
            $newData = $this->petugasInsidenRepository->trackPetugasInsidenByInsiden($this->insiden_id);
            
            // Transform data to ensure consistent structure
            $transformedData = collect($newData)->map(function ($item) {
                return [
                    'latitude' => (float) ($item['latitude'] ?? $item->latitude ?? 0),
                    'longitude' => (float) ($item['longitude'] ?? $item->longitude ?? 0),
                    'nama_petugas' => $item['nama_petugas'] ?? $item->nama_petugas ?? 'Unknown Officer',
                    'foto' => $item['foto'] ?? $item->foto ?? null,
                    'no_seri' => $item['no_seri'] ?? $item->no_seri ?? 'N/A',
                    'suhu' => $item['suhu'] ?? $item->suhu ?? 'N/A',
                    'kualitas_udara' => $item['kualitas_udara'] ?? $item->kualitas_udara ?? 'N/A',
                    'status_text' => $item['status_text'] ?? $item->status_text ?? 'N/A',
                    'status_color' => $item['status_color'] ?? $item->status_color ?? 'text-secondary',
                ];
            })->toArray();

            // Only update if data has actually changed
            if (json_encode($this->petugasInsidenData) !== json_encode($transformedData)) {
                $this->petugasInsidenData = $transformedData;
                $this->lastUpdated = now()->timestamp;

                // Dispatch event to JavaScript
                $this->dispatch('petugasDataUpdated', [
                    'data' => $this->petugasInsidenData,
                    'timestamp' => $this->lastUpdated
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error loading petugas insiden data: ' . $e->getMessage());
            $this->petugasInsidenData = [];
        }
    }

    #[On('refreshMapData')]
    public function refreshMapData($event)
    {
        // This method receives data from BerandaController
        if (isset($event['petugasData'])) {
            $this->petugasInsidenData = $event['petugasData'];
            $this->lastUpdated = $event['timestamp'] ?? now()->timestamp;
            
            // Dispatch to JavaScript
            $this->dispatch('petugasDataUpdated', [
                'data' => $this->petugasInsidenData,
                'timestamp' => $this->lastUpdated
            ]);
        }
    }

    // Method ini akan dipanggil oleh wire:poll
    public function refreshData()
    {
        $this->loadPetugasInsidenData();
    }

    public function hydrate()
    {
        $this->loadPetugasInsidenData();
    }

    public function render()
    {
        return view('livewire.komando.map');
    }
}