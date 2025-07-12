<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Perangkat extends Model
{
    use SoftDeletes;
    protected  $table = 'perangkat';

    protected $fillable = [
        'no_seri',
        'kondisi',
    ];




    public function logPerangkat()
    {
        return $this->hasMany(PetugasInsiden::class, 'id_perangkat', 'id');
    }
}
