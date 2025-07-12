<div>
    <x-alerts.dispatch-message />
    <div class="card card-outline card-secondary">
        <div class="card-header">
            <button class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#tambah">
                <i class="bi bi-plus"></i>
                <span>Tambah Data</span>
            </button>
            <div class="card-title">
                <h2> DATA USER</h2>
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
                                    Nama
                                    @if ($sortField === 'nama')
                                        <i class="bi bi-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($list_users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 + ($list_users->currentPage() - 1) * $list_users->perPage() }}
                                    </td>
                                    <td>{{ $user->nama }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if ($user->role === 1)
                                            <span class="badge bg-primary">Admin</span>
                                        @else
                                            <span class="badge bg-success">Komando</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" wire:click="editData({{ $user->id }})" data-bs-toggle="modal" data-bs-target="#tambah">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#hapusModal"
                                            wire:click="confirmDelete({{ $user->id }})">
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
                    {{ $list_users->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- modal tambah & edit data user --}}
    <x-modals.modal button="{{ $user_id ? 'Simpan Perubahan' : 'Simpan' }}" id="tambah"
        title="{{ $user_id ? 'Edit Data User' : 'Tambah Data User' }}" action="saveData">
        <div class="row">

            <div class="col-md-6">

                <x-forms.input model="nama" label="Nama" placeholder="Nama" />
            </div>
           <div class="col-md-6">
            <x-forms.input model="username" label="Username" placeholder="Username" />
           </div>
            <div class="col-md-6">
                <x-forms.input type="email" model="email" label="Email" placeholder="Email" />
            </div>
           <div class="col-md-6">
            <x-forms.select model="role" label="Role" placeholder="Role">
                <option value="">Pilih Role</option>
                <option value="1">Admin</option>
                <option value="0">User</option>
            </x-forms.select>
        </div>
        <x-forms.input type="password" model="password" label="Password" placeholder="Password" />
        @if($user_id)
            <span class="text-muted text-sm">Biarkan kosong jika tidak ingin mengganti password

            </span>
            @endif


        </div>
    </x-modals.modal>
    {{-- modal delete data user --}}
    <x-modals.modalhapus id="hapusModal" click="deleteData({{ $selectedId }})" />
</div>
