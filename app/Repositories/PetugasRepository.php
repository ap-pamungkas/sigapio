<?php

namespace App\Repositories;
use App\Models\Petugas;
use App\Services\LogActivityService;
use App\Traits\QueryHelper;
use Illuminate\Support\Facades\Storage;

class PetugasRepository extends Repository  {
    use QueryHelper;


    protected $logActivityService;
    public function __construct(){
        $this->logActivityService = new logActivityService();
    }

    public function getPetugas($search, $perPage, $sortField = null, $sortDirection = null)
    {
        $query = Petugas::query(); // Eager load jabatan relationship to avoid N+1 queries

        // Apply search filter if provided
        if (!empty($search)) {
            $searchableFields = [
                'nama',
                'foto',
                'no_telepon',
                'alamat',
                'tgl_lahir',
                'jenis_kelamin',
                'status'
            ];

            $query->where(function ($q) use ($search, $searchableFields) {
                // Build search conditions for main fields
                $q->where(function($subQ) use ($search, $searchableFields) {
                    foreach ($searchableFields as $field) {
                        $subQ->orWhere($field, 'like', "%{$search}%");
                    }
                });
            });
        }

        // Use the trait methods for sorting and pagination
        $this->applySorting($query, $sortField ?? $this->sortField, $sortDirection ?? $this->sortDirection);

        return $this->paginateResults($query, $perPage);
    }


    public function createPetugas($data){
        // Simpan foto jika ada
        if (isset($data['foto']) && $data['foto'] instanceof \Illuminate\Http\UploadedFile) {
            $file = $data['foto'];
            // Simpan foto dan dapatkan path lengkap
            $data['foto'] = $file->store('foto_petugas', 'public');
        }

        $petugas = Petugas::create($data);
        $this->logActivityService->logActivity(
            $petugas,
            'create',
            [

            $petugas['nama'] => $petugas->nama,
            ],
            'nama'
        );
        return $petugas;
    }


    public function updatePetugas($id, array $data)
    {
        $petugas = Petugas::findOrFail($id);
        if (array_key_exists('foto', $data)) {
            $this->handlePetugasFotoUpdate($petugas, $data['foto']);
        }
        // Hapus key 'foto' dari data untuk mencegah overwrite jika tidak ada perubahan
        unset($data['foto']);
        $petugas->update($data);
        $this->logActivityService->logActivity(
            $petugas,
            'update',
            [

            $petugas['nama'] => $petugas->nama,
            ],
            'nama'
        );
        return $petugas;
    }



    public function deletePetugas($id)
    {
        $petugas = Petugas::findOrFail($id);
        if ($petugas->foto && Storage::disk('public')->exists($petugas->foto)) {
            Storage::disk('public')->delete($petugas->foto);
        }
        $this->logActivityService->logActivity(
            $petugas,
            'delete',
            [

            $petugas['nama'] => $petugas->nama,
            ],
            'nama'
        );
        return $petugas->delete();
    }

    private function handlePetugasFotoUpdate($petugas, $foto)
    {
        $disk = Storage::disk('public');
        // Hapus foto lama jika ada
        if ($petugas->foto && $disk->exists($petugas->foto)) {
            $disk->delete($petugas->foto);
        }
        if ($foto instanceof \Illuminate\Http\UploadedFile) {
            // Simpan foto baru
            $path = $foto->store('petugas', 'public');
            $petugas->foto = $path;
        } elseif (is_null($foto)) {
            // Set foto menjadi null jika dihapus
            $petugas->foto = null;
        }
        $petugas->save();
    }



    public function updateStatus($petugasId)
{
    $petugas = Petugas::find($petugasId);
    if (is_null($petugas)) {
        return false; // Mengembalikan false jika petugas tidak ditemukan
    }
    // Toggle status antara 'Aktif' dan 'Tidak Aktif'
    $petugas->status = $this->toggleStatus($petugas->status);
    $this->logActivityService->logActivity(
        $petugas,
        'update status',
        [

        $petugas['status'] => $petugas->status,
        ],
        'nama'
    );
    $petugas->save();
    return $petugas; // Mengembalikan objek Petugas setelah diperbarui
}
private function toggleStatus($currentStatus)
{
    return $currentStatus === 'Aktif' ? 'Tidak Aktif' : 'Aktif';

}

}
