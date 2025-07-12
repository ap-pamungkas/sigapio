<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas'; // Pastikan nama tabel sesuai dengan yang ada di database
    protected $fillable = [
        'nama',
        'no_telepon',
        'alamat',
        'tgl_lahir',
        'foto',
        'jenis_kelamin',
    ];
    public static $rules = [
        'nama' => 'required | max:50',
        'no_telepon' => 'required |max:15',
        'alamat' => 'required ',
        'tgl_lahir' => 'required ',
        'jenis_kelamin' => 'required',
        'foto' => 'required |mimes:jpg,png,jpeg',
    ];

    public static $rulesUpdate = [
        'nama' => 'required | max:50',
        'no_telepon' => 'required|max:15',
        'alamat' => 'required ',
        'tgl_lahir' => 'required ',
        'jenis_kelamin' => 'required',
        'foto' => 'nullable ',
    ];

    public static $messages = [

            'nama.required' => 'nama wajib di isi ',
            'nama.max' => 'nama tidak boleh lebih dari 50 karakter',
            'no_telepon.max' => 'no telepon tidak boleh lebih dari 15 karakter',
            'no_telepon.required' => 'no telepon wajib di isi',
            'alamat.required' => 'alamat wajib di isi',
            'tgl_lahir.required' => 'tanggal lahir wajib di isi',
            'jenis_kelamin.required' => 'jenis kelamin wajib di isi',
            'foto.required' => 'foto wajib di isi',
            'foto.mimes' => 'foto harus berformat jpg,png,jpeg'


    ];



    public function petugasInsiden()
    {
        return $this->hasMany(PetugasInsiden::class, 'petugas_id', 'id');
    }
}
