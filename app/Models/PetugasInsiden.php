<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PetugasInsiden extends Model
{
    protected $table = 'petugas_insiden'; // Pastikan nama tabel sesuai dengan yang ada di database
    protected $fillable = [
        'petugas_id',
        'insiden_id',
        'perangkat_id',
        'status',
    ];

    protected $with = [
        'petugas',
        'insiden',
        'perangkat',
        'insidenLog',
    ];
    public function petugas()
    {
        return $this->belongsTo(Petugas::class, 'petugas_id', 'id');
    }

    public function perangkat()
    {
        return $this->belongsTo(Perangkat::class, 'perangkat_id', 'id');
    }

    public function insiden()
    {
        return $this->belongsTo(Insiden::class, 'insiden_id', 'id');
    }

    public function insidenLog()
    {
        return $this->hasMany(LogInsiden::class, 'petugas_insiden_id');
    }
}
