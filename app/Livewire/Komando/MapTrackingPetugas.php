<?php

namespace App\Livewire\Komando;

use App\Models\Insiden;
use App\Repositories\PetugasInsidenRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

class MapTrackingPetugas extends Component
{

    #[Title("Map Tracking Petugas")]
    #[Layout("components.layouts.komando")]


    public $insiden_id;

    public $insiden;

    public $latitude;
    public $longitude;
    public $petugasInsidenData = [];

    protected  $petugasInsidenRepository;

    public function boot(PetugasInsidenRepository $petugasInsidenRepository)
    {
        $this->petugasInsidenRepository = $petugasInsidenRepository;


    }


    public function mount($insiden_id)
    {
        $this->insiden = Insiden::findOrFail($insiden_id);
        $this->latitude = $this->insiden->latitude;
        $this->longitude = $this->insiden->longitude;

       $this->loadPetugasInsidenData();


    }


      public function loadPetugasInsidenData()
    {
       $this->petugasInsidenData = $this->petugasInsidenRepository->trackPetugasInsidenByInsiden($this->insiden_id);


    }


    public function render()
    {
        return view('livewire.komando.map-tracking-petugas');
    }
}
