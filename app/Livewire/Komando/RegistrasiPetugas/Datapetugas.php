<?php

namespace App\Livewire\Komando\RegistrasiPetugas;

use App\Repositories\PetugasInsidenRepository;
use Livewire\Component;

class Datapetugas extends Component
{
    public $insidenId;

    public $petugasInsidenData = [];

    protected $listeners = ['refreshPetugasInsidenData' => '$refresh'];

    protected PetugasInsidenRepository $petugasInsidenRepository;

    public function boot(PetugasInsidenRepository $petugasInsidenRepository)
    {
        $this->petugasInsidenRepository = $petugasInsidenRepository;
    }

    public function mount($insidenId)
    {
        $this->insidenId = $insidenId;
        // Load petugas insiden data when the component is mounted

        $this->loadPetugasInsidenData();
    }


    public function hydrate()
    {
        $this->loadPetugasInsidenData();
    }

    public function loadPetugasInsidenData()
    {
        $this->petugasInsidenData = $this->petugasInsidenRepository->getPetugasInsidenByInsiden($this->insidenId);
    }

    public function render()
    {
        return view('livewire.komando.registrasi-petugas.data-petugas');
    }
}
