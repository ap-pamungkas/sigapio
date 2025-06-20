<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait QueryHelper
{
     // Properties for sorting (add these to any class using this trait)
 public $sortField = 'id';
 public $sortDirection = 'asc';

 /**
  * Toggle sorting direction or set new sort field
  *
  * @param string $field
  * @return void
  */
 public function sortBy($field)
 {
     if ($this->sortField === $field) {
         $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
     } else {
         $this->sortField = $field;
         $this->sortDirection = 'asc';
     }
 }
    public function applySorting(Builder $query, $sortField = 'id', $sortDirection = 'asc')
    {
        $query->orderBy($sortField, $sortDirection);
    }

    public function paginateResults(Builder $query, $perPage = 10)
    {
        return $query->paginate($perPage);
    }
}
