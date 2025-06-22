<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Insiden extends Model
{
    use SoftDeletes;
    protected $table = 'insiden';
    protected $fillable = [
        'nama_insiden',
        'keterangan',
        'latitude',
        'longitude',
        'status'
    ];

    
    public function petugasInsiden(){
        return $this->hasMany(PetugasInsiden::class, 'insiden_id', 'id');
    }

}
