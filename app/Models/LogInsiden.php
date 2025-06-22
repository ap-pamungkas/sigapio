<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LogInsiden extends Model
{
    use SoftDeletes;
    protected $table = 'log_insiden'; // Pastikan nama tabel sesuai dengan yang ada di database
    protected $fillable = [
        'insiden_id',
        'petugas_insiden_id',
        'latitude',
        'longitude',
        'suhu', 
        'kualitas_udara',
        'darurat',
    ];

    public function insiden()
    {
        return $this->belongsTo(Insiden::class, 'insiden_id', 'id');
    }

    public function petugasInsiden()
    {
        return $this->belongsTo(PetugasInsiden::class,'petugas_insiden_id', 'id');
    }
}
