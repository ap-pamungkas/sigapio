<div>
    <x-alerts.dispatch-message />
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <button class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#tambah">
                <i class="bi bi-plus"></i>
                <span>Tambah Data</span>
            </button>
            <div class="card-title">
                <h2> DATA PETUGAS</h2>
            </div>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="table-responsive">
                    <x-table.table searching>
                        <thead>
                            <tr>
                                <th width='50px'>No</th>
                                <th wire:click="sortBy('nama')" style="cursor: pointer;">
                                    Nama Petugas
                                    @if ($sortField === 'nama')
                                        <i class="bi bi-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>
                                    No Telepon
                                </th>
                                <th>Jenis Kelamin</th>
                                <th wire:click="sortBy('jenis_kelamin')" style="cursor: pointer;">Tanggal Lahir

                                </th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($list_petugas as $index => $petugas)
                                <tr>
                                    <td>{{ $index + 1 + ($list_petugas->currentPage() - 1) * $list_petugas->perPage() }}
                                    </td>
                                    <td>{{ $petugas->nama }}</td>
                                    <td>{{ $petugas->no_telepon ?? '-'   }}</td>
                                    <td>{{ $petugas->jenis_kelamin }}</td>
                                    <td>{{ $petugas->tgl_lahir }}</td>
                                    <td>{{ $petugas->status }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                            href="{{ route('admin.petugas.show', $petugas->id) }}">
                                            <i class="bi bi-info-circle"></i>
                                        </a>
                                        <button class="btn btn-warning btn-sm"
                                            wire:click="editData({{ $petugas->id }})" data-bs-toggle="modal"
                                            data-bs-target="#tambah">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#hapusModal"
                                            wire:click="confirmDelete({{ $petugas->id }})">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>


                                <!-- Modal Hapus Data -->

                            @empty
                                <tr class="text-center">
                                    <td colspan="7">Tidak ada data untuk ditampilkan!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </x-table.table>
                    {{ $list_petugas->links() }}
                </div>
            </div>
        </div>
    </div>
    {{-- modal save data petugas --}}
    <x-modals.modal button="{{ $petugas_id ? 'Simpan Perubahan' : 'Simpan' }}" id="tambah" size="modal-xl"
        title="{{ $petugas_id ? 'Edit Data Petugas' : 'Tambah Data Petugas' }}" action="saveData">
        <div class="row">
            <div class="col-md-4">
                <x-forms.input model="nama" label="Nama Petugas" placeholder="Nama Petugas" />
            </div>
            <div class="col-md-4">
                <x-forms.input type="number" model="no_telepon" label="No Telepon" placeholder="No Telepon" />
            </div>

            <div class="col-md-4">
               <x-forms.select model="jenis_kelamin" label="Jenis Kelamin" placeholder="Jenis Kelamin">
    <option value="">Pilih Jenis Kelamin</option>
    <option value="Laki-laki" {{ $jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
    <option value="Perempuan" {{ $jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
</x-forms.select>

            </div>
            <div class="col-md-6">
                <x-forms.input model="tgl_lahir" type="date" label="Tanggal Lahir" placeholder="Tanggal Lahir" />
            </div>
            <div class="col-md-6">
                <x-forms.input model="foto" type="file" label="Foto" placeholder="Foto" />

            @if ($petugas_id)
                <div class="mt-2">
                    <span>Foto Lama: <a href="{{ url('public/storage/' .$foto) }}" target="_blank">Lihat</a></span>
                </div>

            @endif
            </div>
            <div class="col-md-12">
                <x-forms.text-area model="alamat" label="Alamat" placeholder="Alamat" />
            </div>
        </div>
    </x-modals.modal>
    {{-- modal delete data petugas --}}
    <x-modals.modalhapus id="hapusModal" click="deleteData({{ $selectedId }})" />
</div>
