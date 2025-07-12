<div>
    <x-alerts.dispatch-message />
    <div  class="card card-outline card-secondary">
      <div class="card-header">
        <button class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#tambah">
            <i class="bi bi-plus"></i>
            <span>Tambah Data</span>
        </button>
         <div class="card-title">
           <h2> DATA PERANGKAT</h2>
         </div>
      </div>
      <div class="card-body">
            <div class="row">
                <div class="table-responsive">

                    <x-table.table searching>
                        <thead>
                            <tr>
                                <th width='50px'>No</th>
                                <th wire:click="sortBy('no_seri')" style="cursor: pointer;">
                                   No Seri
                                    @if($sortField === 'nama_jabatan')
                                        <i class="bi bi-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Kondisi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                              @forelse ($list_devices as $index => $device)
                                <tr>
                                    <td>{{ $index + 1 + ($list_devices->currentPage() - 1) * $list_devices->perPage() }}</td>
                                    <td>{{ $device->no_seri }}</td>
                                    <td>{{ $device->kondisi }}</td>
                                    <td>
                                        <button class="btn btn-secondary btn-sm"
                                            wire:click="updateConditions('{{ $device->id }}')" data-bs-toggle="modal"
                                            >
                                            <i class="bi bi-pencil-square"></i> Ubah Kondisi
                                        </button>
                                        <button class="btn btn-warning btn-sm"
                                            wire:click="editData({{ $device->id }})" data-bs-toggle="modal"
                                            data-bs-target="#tambah">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                          <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#hapusModal"
                                        wire:click="confirmDelete({{ $device->id }})">
                                        <i class="bi bi-trash"></i>

                                    </td>
                                </tr>


                                <!-- Modal Hapus Data -->

                            @empty
                                <tr class="text-center">
                                    <td colspan="5">Tidak ada data untuk ditampilkan!</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </x-table.table>
                    {{ $list_devices->links() }}
                </div>
            </div>
      </div>
    </div>

    {{-- modal tambah & edit data jabatan --}}
    <x-modals.modal button="{{ $device_id ? 'Simpan Perubahan' : 'Simpan' }}" id="tambah" title="{{ $device_id ? 'Edit Data Perangkat' : 'Tambah Data Perangkat' }}" action="saveData">
        <x-forms.input model="no_seri" label="No Seri" placeholder="No Seri" />
    </x-modals.modal>

    {{-- modal delete data jabatan --}}
     <x-modals.modalhapus id="hapusModal" click="deleteData({{ $selectedId }})" />
 </div>

