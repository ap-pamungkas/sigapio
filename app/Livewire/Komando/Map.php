<?php

namespace App\Livewire\Komando;

use App\Models\Insiden;
use App\Repositories\PetugasInsidenRepository;
use Livewire\Component;

class Map extends Component
{
    public $insiden;
    public $insiden_id;

    public $latitude;
    public $longitude;

    public $petugasInsidenData = [];
    
    // Property ini penting untuk tracking perubahan
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
            $this->latitude = -0.0263;   // Contoh: koordinat Pontianak
            $this->longitude = 109.3425;
            $this->petugasInsidenData = [];
        }
        
        $this->lastUpdated = now()->timestamp;
    }

    public function loadPetugasInsidenData()
    {
        if ($this->insiden_id) {
            $newData = $this->petugasInsidenRepository
                ->trackPetugasInsidenByInsiden($this->insiden_id);
            
            // Cek apakah data benar-benar berubah
            if ($this->petugasInsidenData !== $newData) {
                $this->petugasInsidenData = $newData;
                $this->lastUpdated = now()->timestamp;
                
                // Dispatch event ke JavaScript
                $this->dispatch('petugasDataUpdated', [
                    'data' => $this->petugasInsidenData,
                    'timestamp' => $this->lastUpdated
                ]);
            }
        }
    }

    // Method ini akan dipanggil oleh wire:poll
    public function refreshData()
    {
        $this->loadPetugasInsidenData();
    }

    public function render()
    {
        // PENTING: Panggil loadPetugasInsidenData() di render
        // agar setiap kali wire:poll trigger, data akan di-update
        $this->loadPetugasInsidenData();
        
        return view('livewire.komando.map');
    }
}