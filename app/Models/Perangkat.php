<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Perangkat extends Model
{
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
