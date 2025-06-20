<?php

namespace App\Livewire\Komando;

use App\Repositories\InsidenRepository;
use App\Traits\DispatchMessage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class InsidenController extends Component
{

    use WithPagination, DispatchMessage;
    #[Title("Komando - Insiden")]
    #[Layout("components.layouts.komando")]

    public $insiden_id;
    public $nama_insiden;
    public $keterangan;
    public $isEditMode = false;
    public  $selectedId;
    public $search = '';

    public $perPage = 10;
    // public $sortField, $sortDirection ;

    public $sortField = 'id';
    public $sortDirection = 'asc';

    public $paginationTheme = 'bootstrap';

    
    protected $insidenRepository;

    public function __construct()
    {
        $this->insidenRepository = new InsidenRepository();
    }

    public function mount(){
        if(session()->has("success")){
            $this->success(session()->get("success"), 2000);
         }
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
    public function render()
    {
        $data['list_insiden'] = $this->insidenRepository->getInsiden(
            $this->search,
            $this->perPage,
            $this->sortField,
            $this->sortDirection
        );


        return view('livewire.komando.insiden.index', $data);
    }

    public function confirmDelete($id)
    {
        $this->selectedId = $id;
    }

    public function close()
    {
        $this->reset();
    }


    public function saveData(){
        $data = [
            'nama_insiden' => $this->nama_insiden,
            'keterangan' => $this->keterangan,
        ];
        switch ($this->insiden_id) {
            case null:
                $this->insidenRepository->createInsiden($data);
                $this->success('data berhasil di simpan');
                $this->resetToCreate();
                break;
            default:
                $this->insidenRepository->updateInsiden($this->insiden_id, $data);
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
        $this->reset(['nama_insiden', 'keterangan']);
    }

    public function deleteData($id)
    {
        $this->insidenRepository->deleteInsiden($id);
        $this->reset();
        $this->resetPage();
        $this->js('
             $(".modal").modal("hide")');
        $this->success('data berhasil di hapus');
    }


    public function resetForm()
    {
        $this->nama_insiden = '';
        $this->keterangan = '';

        // Note that we don't reset $this->jabatans
    }
}
