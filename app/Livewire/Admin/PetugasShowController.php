<?php

namespace App\Livewire\Admin;

use App\Models\Petugas;
use App\Repositories\PetugasRepository;
use App\Traits\DispatchMessage;
use App\Traits\Message;
use Livewire\Attributes\Title;
use Livewire\Component;

class PetugasShowController extends Component
{

    use DispatchMessage;
    public $petugas, $status, $petugasId;


    #[Title("Detail Petugas")]

    private $petugasRepository;


    public function __construct(){
        $this->petugasRepository =  new PetugasRepository();
    }

    public function mount($id)
    {
        $this->petugas = Petugas::find($id);
        if (!$this->petugas) {
            $this->error('Petugas tidak ditemukan!');
            return;
        }
    }
    public function render()
    {
        return view('livewire.admin.petugas.show');
    }


   public function updateStatus($petugasId)
    {
        $petugas = $this->petugasRepository->updateStatus($petugasId);
        if (!$petugas) {
            $this->error('Petugas tidak ditemukan!');
            return;
        }
        $this->status = $petugas->status;  // Update property status in component
        $this->success("Status berhasil diubah menjadi {$this->status}", 1700);
    }
}
