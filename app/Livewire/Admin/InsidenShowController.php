<?php

namespace App\Livewire\Admin;
use App\Repositories\PetugasInsidenRepository;
use Livewire\Component;

class InsidenShowController extends Component
{

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

        return view('livewire.admin.insiden.show');
    }


}
