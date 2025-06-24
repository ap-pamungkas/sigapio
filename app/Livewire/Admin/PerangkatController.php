<?php

namespace App\Livewire\Admin;

use App\Models\Perangkat;
use App\Repositories\PerangkatRepository;
use App\Traits\DispatchMessage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class PerangkatController extends Component
{

    use  WithPagination, DispatchMessage;

    public  $device_id;
    public int $selectedId;
    public string $no_seri;
    public $isEditMode = false;


    public string $search = '';

    public int $perPage = 10;

    public string $sortField = 'id';
    public  String $sortDirection = 'asc';

    public string $paginationTheme = 'bootstrap';
    protected PerangkatRepository $perangkatRepository;



    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }


    public function boot(PerangkatRepository $perangkatRepository)
    {
        $this->perangkatRepository = $perangkatRepository;
    }

    #[Title("Perangkat")]
    public function render()
    {

        $data['list_devices'] = $this->perangkatRepository->getDevices(
            $this->search,
            $this->perPage,
            $this->sortField,
            $this->sortDirection,
            ['Baik', 'Rusak']
        );
        return view('livewire.admin.perangkat.index', $data);
    }




    public function saveData()
    {
        $data = [
            'no_seri' => $this->no_seri,
            'kondisi' => 'Baik', // Default condition
        ];
        $this->validate([
            'no_seri' => 'required|string|max:255',
        ], [
            'no_seri.required' => 'No Seri Wajib diisi.',
        ]);
        switch ($this->device_id) {
            case null:
                $this->perangkatRepository->createDevice($data);
                $this->success('data berhasil di simpan');
                $this->resetToCreate();
                break;
            default:
                $this->perangkatRepository->updateDevice($this->petugas_id, $data);
                $this->success('data berhasil di perbarui');
                break;
        }
        $this->resetForm();
        $this->resetPage();
        $this->js('
           $(".modal").modal("hide")
       ');
    }


    public function editData($id)
    {
        $devices = Perangkat::find($id);
        $this->device_id = $devices->id;
        $this->no_seri = $devices->no_seri;
    }
    public function resetToCreate()
    {
        $this->isEditMode = false;
        $this->reset(['no_seri']);
    }
    public function confirmDelete($id)
    {
        $this->selectedId = $id;
    }
    public function deleteData($id)
    {

        $this->perangkatRepository->deleteDevices($id);

        $this->success('data berhasil di hapus');
        $this->js('
            $(".modal").modal("hide")

        ');
        $this->resetPage();
    }



    public function updateConditions($id)
    {
        $devices = $this->perangkatRepository->updateConditions($id);
        if (!$devices) {
            $this->error('Perangkat tidak ditemukan!');
            return;
        }
        $this->success("Status perangkat berhasil diubah menjadi {$devices->kondisi}", 1700);
    }





    public function close()
    {
        $this->resetPage();
        $this->reset(['search', 'perPage', 'sortField', 'sortDirection']);
    }


    public function resetForm()
    {
        $this->no_seri = '';
    }
}
