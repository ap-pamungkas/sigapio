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



    public function render()
    {
        return view('livewire.komando.map-tracking-petugas');
    }
}