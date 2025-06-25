<?php

namespace App\Livewire\Admin;

use App\Repositories\InsidenRepository;
use App\Traits\DispatchMessage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class InsidenController extends Component
{

    use WithPagination, DispatchMessage;
    #[Title("Admin - Insiden")]

    protected $insidenRepository;
    public  $selectedId;
    public $search = '';
    public $perPage = 10;
    // public $sortField, $sortDirection ;
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $paginationTheme = 'bootstrap';

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function __construct()
    {
        $this->insidenRepository = new InsidenRepository();
    }

    public function render()
    {
        $data['list_insiden'] = $this->insidenRepository->getInsiden(
            $this->search,
            $this->perPage,
            $this->sortField,
            $this->sortDirection
        );


        return view('livewire.admin.insiden.index', $data);
    }

    public function confirmDelete($id)
    {
        $this->selectedId = $id;
    }

    public function close()
    {
        $this->reset();
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
}
