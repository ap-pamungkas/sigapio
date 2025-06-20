<?php

namespace App\Livewire\Komando;

use App\Repositories\PetugasInsidenRepository;
use Livewire\Attributes\{
    Layout,
    Title
};
use Livewire\Component;

class InsidenShowController extends Component
{

    #[Layout('components.layouts.komando')]
    #[Title('Detail Insiden')]
    public $insiden;
    public $logData;
    protected $petugasInsidenRepository;

    public function __construct()
    {
        $this->petugasInsidenRepository = new PetugasInsidenRepository();
    }
    public function mount($id)
    {
        $this->insiden = $this->petugasInsidenRepository->getInsidenById($id);
        $this->logData = $this->petugasInsidenRepository->getLogByInsidenId($id);
    }
    public function render()
    {
        return view('livewire.komando.insiden.show');
    }
}
