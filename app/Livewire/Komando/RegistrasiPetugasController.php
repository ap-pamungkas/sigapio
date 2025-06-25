<?php

namespace App\Livewire\Komando;

use App\Livewire\Komando\RegistrasiPetugas\Datapetugas;
use App\Livewire\Komando\RegistrasiPetugas\PetugasInsidenData;
use App\Models\Insiden;
use App\Models\Perangkat;
use App\Models\Petugas;
use App\Models\PetugasInsiden;
use App\Traits\DispatchMessage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Repositories\PetugasInsidenRepository;
use App\Repositories\InsidenRepository;

class RegistrasiPetugasController extends Component
{
    use DispatchMessage;

    #[Title("Registrasi Petugas")]
    #[Layout("components.layouts.komando")]

    public $devicesList = [];
    public $petugasList = [];
    public $insiden;
    public $selectedDevice;
    public $selectedPetugas;
    public $petugasInsidenData = [];

    protected $petugasInsidenRepository;
    protected $insidenRepository;

    public function boot(){
        $this->petugasInsidenRepository = new PetugasInsidenRepository();
        $this->insidenRepository = new InsidenRepository();
    }

    public function mount($insiden_id)
    {
        $this->insiden = Insiden::findOrFail($insiden_id);
        $this->devicesList = Perangkat::where('kondisi', '=', 'Baik')->pluck('no_seri', 'id');
        $this->petugasList = Petugas::where('status', '=', 'Aktif')->pluck('nama', 'id');
    }

    public function render()
    {
        return view('livewire.komando.registrasi-petugas.index');
    }
 public function confirm()
{
    $this->validate([
        'selectedDevice' => 'required',
        'selectedPetugas' => 'required',
    ]);

    // Cek apakah kombinasi perangkat sudah pernah ditambahkan untuk insiden ini
    $exists = PetugasInsiden::where('insiden_id', $this->insiden->id)
        ->where(function ($query) {
            $query->where('petugas_id', $this->selectedPetugas)
                  ->orWhere('perangkat_id', $this->selectedDevice);
        })
        ->exists();

    if ($exists) {
        session()->flash('error', 'Petugas atau perangkat sudah terdaftar dalam insiden ini.');
        return;
    }

    $data = [
        'insiden_id' => $this->insiden->id,
        'petugas_id' => $this->selectedPetugas,
        'perangkat_id' => $this->selectedDevice,
    ];

    $this->petugasInsidenRepository->createPetugasInsiden($data);
   session()->flash('success', 'Petugas dan perangkat berhasil ditambahkan.');
    $this->dispatch('refreshPetugasInsidenData')->to(Datapetugas::class);
    $this->reset(['selectedDevice', 'selectedPetugas']);

}




public function finalizeTask(){
    $this->insidenRepository->finalizeTaskInsiden($this->insiden->id);
    session()->flash('success', 'Tugas selesai berhasil dilakukan.');
    return redirect()->route('komando.insiden');
}

}
