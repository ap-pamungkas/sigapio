<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Repository;

use App\Traits\QueryHelper;

class UserRepository extends Repository
{

    use QueryHelper;

 


    public function __construct()
    {
       parent::__construct();   
    }

    public function getusers(?string $search, int $perPage, ?string $sortField = null, ?string $sortDirection = null)
    {
        $query = User::query();

        if (!empty($search)) {
            $query->where('nama', 'like', "%{$search}%")
                ->orWhere("username", "like", "%{$search}%");
        }

        $this->applySorting($query, $sortField ?? $this->sortField, $sortDirection ?? $this->sortDirection);

        return $this->paginateResults($query, $perPage);
    }


    public function createUser(array $data)
    {
        $user = User::create($data);
        $this->logActivityService->logActivity(
            $user,
            'created',
            [
                $user['nama'] => $user->nama,
            ],
            'nama'
        );
        return $user;
    }

    public function updateUser($id, array $data)
    {
        $user = User::find($id);
        $user->update($data);
        $this->logActivityService->logActivity(
            $user,
            'updated',
            [
                $user['nama'] => $user->nama,
            ],
            'nama'
        );
        return $user;
    }


    public function deleteUser($id)
    {
        $user = User::find($id);
         $this->logActivityService->logActivity(
            $user,
            'deleted',
            [
                $user['id'] => $user->id,
                $user['nama'] => $user->nama,
                $user['username'] => $user->username,
                
            ],
            'nama'
        );
        $user->delete();
       
    }


}
