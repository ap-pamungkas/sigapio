<div>
    <x-alerts.dispatch-message />
    <div  class="card card-outline card-secondary">
      <div class="card-header">
        <button class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#tambah">
            <i class="bi bi-plus"></i>
            <span>Tambah Data</span>
        </button>
         <div class="card-title">
           <h2> DATA JABATAN</h2>
         </div>
      </div>
      <div class="card-body">
            <div class="row">
                <div class="table-responsive">
                  
                    <x-table.table searching>
                        <thead>
                            <tr>
                                <th width='50px'>No</th>
                                <th wire:click="sortBy('nama_jabatan')" style="cursor: pointer;">
                                    Nama Jabatan
                                    @if($sortField === 'nama_jabatan')
                                        <i class="bi bi-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($list_jabatan as $index => $jabatan)
                                <tr>
                                    <td>{{ $index + 1 + (($list_jabatan->currentPage() - 1) * $list_jabatan->perPage()) }}</td>
                                    <td>{{ $jabatan->nama_jabatan }}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" wire:click="editData({{ $jabatan->id }})" data-bs-toggle="modal" data-bs-target="#tambah">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#hapusModal"
                                        wire:click="confirmDelete({{ $jabatan->id }})">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="text-center">
                                    <td colspan="3">Tidak ada data untuk ditampilkan!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </x-table.table>
                    {{ $list_jabatan->links() }}
                </div>
            </div>
      </div>
    </div>

    {{-- modal tambah & edit data jabatan --}}
    <x-modals.modal button="{{ $jabatan_id ? 'Simpan Perubahan' : 'Simpan' }}" id="tambah" title="{{ $jabatan_id ? 'Edit Data Gudang' : 'Tambah Data Gudang' }}" action="saveData">
        <x-forms.input model="nama_jabatan" label="Nama Jabatan" placeholder="Nama Jabatan" />
    </x-modals.modal>

    {{-- modal delete data jabatan --}}
     <x-modals.modalhapus id="hapusModal" click="deleteData({{ $selectedId }})" />
 </div>
