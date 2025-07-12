<?php

namespace App\Repositories;

use App\Models\Insiden;
use App\Repositories\Repository;
use App\Services\LogActivityService;
use App\Traits\QueryHelper;

class InsidenRepository extends Repository
{
    use QueryHelper;

   
    public function __construct()
    {
       parent::__construct();   
    }

    public function getInsiden(?string $search, int $perPage, ?string $sortField = null, ?string $sortDirection = null)
    {
        $query = Insiden::query();

        if (!empty($search)) {
            $query->where('nama_insiden', 'like', "%{$search}%")
                ->orWhere("keterangan", "like", "%{$search}%");
        }

        $this->applySorting($query, $sortField ?? $this->sortField, $sortDirection ?? $this->sortDirection);

        return $this->paginateResults($query, $perPage);
    }


    public function createInsiden($data)
    {
        $insiden = Insiden::create($data);
        $this->logActivityService->logActivity(
            $insiden,
            'created',
            [
                $insiden['nama_insiden'] => $insiden->nama_insiden,   
            ],
            'nama_insiden'
        );
        return $insiden;
    }


    public function updateInsiden($id, $data)
    {
        $insiden = Insiden::find($id);
        $insiden->update($data);
        $this->logActivityService->logActivity(
            $insiden,
            'update',
            [
                $insiden['nama_insiden'] => $insiden->nama_insiden,
            ],
            'nama_insiden'
        );
        return $insiden;
    }

    public function finalizeTaskInsiden($id){
        $insiden = Insiden::find($id);
        $insiden->update(['status' => true]);
        $this->logActivityService->logActivity(
            $insiden,
            'update',
            [
                $insiden['nama_insiden'] => $insiden->nama_insiden,
            ],
            'nama_insiden'
        );
        return $insiden;
    }


    public function deleteInsiden($id)
    {
        $insiden = Insiden::find($id);
        
        $this->logActivityService->logActivity(
            $insiden,
            'deleted',
            [
                $insiden['nama_insiden'] => $insiden->nama_insiden,
            ],
            'nama_insiden'
        );
        $insiden->delete();
    }
}
