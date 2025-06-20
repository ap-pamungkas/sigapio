<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Repositories\UserRepository;
use App\Traits\DispatchMessage;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class UserController extends Component
{
    use WithPagination, DispatchMessage;
    #[Title("Admin - User Manajemen")]

    protected $userRepository;

    public function boot()
    {
        $this->userRepository = new UserRepository();
    }

public  $selectedId;
public $user_id, $nama, $username, $email, $role, $password;

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


    public function render()
    {
        $data['list_users'] = $this->userRepository->getUsers(
            $this->search,
            $this->perPage,
            $this->sortField,
            $this->sortDirection
        );


        return view('livewire.admin.user.index', $data);
    }

    public function saveData(){
    $this->validate([
        'nama' => 'required',
        'username' => 'required|unique:users,username,' . $this->user_id,
        'email' => 'required|email|unique:users,email,' . $this->user_id,
        'role' => 'required',
        'password' => $this->user_id ? 'nullable|min:6' : 'required|min:6',
    ]);

    $data = [
        'nama' => $this->nama,
        'username' => $this->username,
        'email' => $this->email,
        'role' => $this->role,
    ];

    // Hanya update password jika ada isinya
    if ($this->password) {
        $data['password'] = bcrypt($this->password);
    }

    if ($this->user_id) {
        $this->userRepository->updateUser($this->user_id, $data);
        $this->success('Data berhasil di update');
    } else {
        $this->userRepository->createUser($data);
        $this->success('Data berhasil di simpan');
    }

    $this->reset();
    $this->resetPage();
    $this->js('$(".modal").modal("hide")');
}

    public function editData($id){
        $user = User::find($id);
        $this->user_id = $user->id;
        $this->nama = $user->nama;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->role;

        $this->password = ''; // Reset password field on edit
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
        $this->userRepository->deleteUser($id);
        $this->reset();
        $this->resetPage();
        $this->js('
             $(".modal").modal("hide")');
        $this->success('data berhasil di hapus');
    }

}
