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

    public $insiden_id = '';
    public string $nama_insiden = '';
    public string $keterangan = '';
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
        $this->validate([
            'nama_insiden' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:500',

        ],
    [
            'nama_insiden.required' => 'Nama insiden harus diisi',
            'nama_insiden.string' => 'Nama insiden harus berupa teks',
            'nama_insiden.max' => 'Nama insiden tidak boleh lebih dari 255 karakter',
            'keterangan.string' => 'Keterangan harus berupa teks',
            'keterangan.max' => 'Keterangan tidak boleh lebih dari 500 karakter',
    ]);

                $this->insidenRepository->createInsiden( $data);
                $this->success('data berhasil di perbarui');
        $this->resetForm();
        $this->resetPage();
        $this->js('
            $(".modal").modal("hide")
        ');
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


    }
}
