<?php

namespace App\Livewire\Admin;

use App\Models\Petugas;
use App\Repositories\PetugasRepository;
use App\Traits\DispatchMessage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class PetugasController extends Component
{
    use DispatchMessage, WithFileUploads, WithPagination;
    #[Title("Admin - Petugas")]

    public $selectedId;
    public $petugas_id;
    public $nama;
    public $alamat;
    public $no_telepon;
    public $tgl_lahir;
    public $jenis_kelamin;
    public  $foto;

    public $isEditMode = false;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $paginationTheme = 'bootstrap';

    protected $petugasRepository;

    public function __construct(){
        $this->petugasRepository = new PetugasRepository();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function close()
    {
        $this->reset();
        $this->resetValidation();
    }

    public function saveData()
    {
        $data = [
            'nama' => $this->nama,
            'no_telepon'=> $this->no_telepon,
            'alamat' => $this->alamat,
            'tgl_lahir' => $this->tgl_lahir,
            'jenis_kelamin' => $this->jenis_kelamin,
            'foto' => $this->foto
        ];

        switch ($this->petugas_id) {
            case null:
                $this->validate(Petugas::$rules, Petugas::$messages);
                $this->petugasRepository->createPetugas($data);
                $this->success('data berhasil di simpan');
                $this->resetToCreate();
                break;
            default:
                $this->validate(Petugas::$rulesUpdate, Petugas::$messages);
                $this->petugasRepository->updatePetugas($this->petugas_id, $data);
                $this->success('data berhasil di perbarui');
                break;
        }
        $this->resetForm();
        $this->resetPage();
        $this->js('
            $(".modal").modal("hide")
        ');
    }

    public function resetToCreate()
    {
        $this->isEditMode = false;
        $this->reset(['nama', 'no_telepon', 'alamat', 'tgl_lahir', 'jenis_kelamin', 'foto']);
    }

    public function confirmDelete($id){
        $this->selectedId = $id;
    }

    public function deleteData($id){
        $this->petugasRepository->deletePetugas($id);
        $this->success('data berhasil dihapus');
        $this->js('
            $(".modal").modal("hide")
        ');
        $this->resetPage();
        $this->resetForm();
    }

    public function render()
    {
        $data['list_petugas'] = $this->petugasRepository->getPetugas(
            $this->search,
            $this->perPage,
            $this->sortField,
            $this->sortDirection
        );

        return view('livewire.admin.petugas.index', $data);
    }

    public function resetForm()
    {
        $this->nama = '';
        $this->no_telepon = '';
        $this->alamat = '';
        $this->tgl_lahir = '';
        $this->jenis_kelamin = '';
        $this->foto = null;
        $this->petugas_id = null;
    }

    public function editData($id){
        $petugas = Petugas::find($id);
        $this->petugas_id = $petugas->id;
        $this->nama = $petugas->nama;
        $this->no_telepon = $petugas->no_telepon;
        $this->alamat = $petugas->alamat;
        $this->tgl_lahir = $petugas->tgl_lahir;
        $this->jenis_kelamin = $petugas->jenis_kelamin;

        $this->foto = $petugas->foto;
    }
}
