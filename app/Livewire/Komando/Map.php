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
    }

    public function loadPetugasInsidenData()
    {
        if ($this->insiden_id) {
            $this->petugasInsidenData = $this->petugasInsidenRepository
                ->trackPetugasInsidenByInsiden($this->insiden_id);
        }
    }

    // Method ini akan dipanggil secara otomatis oleh wire:poll
    public function render()
    {
        $this->loadPetugasInsidenData();
        return view('livewire.komando.map');
    }

    // Method untuk refresh data secara manual
    public function refreshData()
    {
        $this->loadPetugasInsidenData();
        $this->dispatch('petugasDataUpdated', $this->petugasInsidenData);
    }

    // Method untuk update data petugas saja tanpa reload seluruh komponen
    public function updatePetugasData()
    {
        $this->loadPetugasInsidenData();
        $this->dispatch('petugasDataUpdated', $this->petugasInsidenData);
    }
}
