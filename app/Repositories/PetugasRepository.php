<?php

namespace App\Repositories;

use App\Models\Petugas;
use App\Traits\QueryHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PetugasRepository extends Repository
{
    use QueryHelper;



    public function __construct()
    {
        parent::__construct();
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
                $q->where(function ($subQ) use ($search, $searchableFields) {
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




public function createPetugas($data)
{
    if (isset($data['foto']) && $data['foto'] instanceof \Illuminate\Http\UploadedFile) {
        $file = $data['foto'];
        if ($file->isValid()) {
            // Bersihkan nama file
            $fileName = time() . '_' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $file->getClientOriginalName());
            $destinationPath = public_path('foto_petugas');
            $fullPath = $destinationPath . '/' . $fileName;

            // Cegah konflik nama file
            if (file_exists($fullPath)) {
                $fileName = time() . '_' . uniqid() . '_' . preg_replace('/[^A-Za-z0-9\-\_\.]/', '', $file->getClientOriginalName());
            }

            // Buat folder jika belum ada
            if (!file_exists($destinationPath)) {
                Log::info('Membuat folder: ' . $destinationPath);
                mkdir($destinationPath, 0755, true);
            }

          

            try {
                // Gunakan storeAs sebagai alternatif
                $file->storeAs('foto_petugas', $fileName, 'local');
                $data['foto'] = 'foto_petugas/' . $fileName;
            } catch (\Exception $e) {
              
                throw new \Exception('Gagal menyimpan file: ' . $e->getMessage());
            }
        } else {
           
            throw new \Exception('File tidak valid atau gagal diunggah.');
        }
    }

    $petugas = Petugas::create($data);
    $this->logActivityService->logActivity(
        $petugas,
        'create',
        [
            'nama' => $petugas->nama,
        ],
        'nama'
    );
    return $petugas;
}

 public function updatePetugas($id, array $data)
{
    $petugas = Petugas::findOrFail($id);

    // Tangani foto jika ada
    if (array_key_exists('foto', $data)) {
        $this->handlePetugasFotoUpdate($petugas, $data['foto']);
        unset($data['foto']); // Mencegah overwrite field foto secara langsung
    }

    // Update data lainnya
    $petugas->update($data);

    // Logging
    $this->logActivityService->logActivity(
        $petugas,
        'update',
        [
            'nama' => $petugas->nama,
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
    $folder = 'foto_petugas';
    $disk = Storage::disk('local'); // storage/app/

    // Ambil path file lama dari DB
    $foto_lama = $petugas->foto;

    // Hapus foto lama (jika ada)
    if ($foto_lama && $disk->exists($foto_lama)) {
        $disk->delete($foto_lama);
    }

    // Upload foto baru
    if ($foto instanceof \Illuminate\Http\UploadedFile) {
        if ($foto->isValid()) {
            $ext = $foto->getClientOriginalExtension();
            $filename = time() . '_' . uniqid() . '.' . $ext;
            $path = $folder . '/' . $filename;

            // Simpan file ke storage/app/foto_petugas
            $disk->putFileAs($folder, $foto, $filename);

            // Simpan path relatif
            $petugas->foto = $path;
        } else {
            throw new \Exception('File foto tidak valid.');
        }
    } elseif (is_null($foto)) {
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
