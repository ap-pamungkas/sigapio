<div>
    <x-alerts.dispatch-message />
    <div  class="card card-outline card-secondary">
      <div class="card-header">
        {{-- <button class="btn btn-success btn-sm float-end" data-bs-toggle="modal" data-bs-target="#tambah">
            <i class="bi bi-plus"></i>
            <span>Tambah Data</span>
        </button> --}}
         <div class="card-title">
           <h2> DATA INSIDEN</h2>
         </div>
      </div>
      <div class="card-body">
            <div class="row">
                <div class="table-responsive">
                  
                    <x-table.table searching>
                        <thead>
                            <tr>
                                <th width='50px'>No</th>
                                <th wire:click="sortBy('nama_insiden')" style="cursor: pointer;">
                                    Nama Insiden
                                    @if($sortField === 'nama_insiden')
                                        <i class="bi bi-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                    @endif
                                </th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($list_insiden as $index => $insiden)
                                <tr>
                                    <td>{{ $index + 1 + (($list_insiden->currentPage() - 1) * $list_insiden->perPage()) }}</td>
                                    <td>{{ $insiden->nama_insiden }}</td>
                                    <td>{{ $insiden->keterangan }}</td>
                                    <td>
                                        <a class="btn btn-primary btn-sm"
                                        href="{{ route('admin.insiden.show', $insiden->id) }}">
                                        <i class="bi bi-info-circle"></i>
                                    </a>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#hapusModal"
                                        wire:click="confirmDelete({{ $insiden->id }})">
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
                    {{ $list_insiden->links() }}
                </div>
            </div>
      </div>
    </div>

   
    {{-- modal delete data insiden --}}
     <x-modals.modalhapus id="hapusModal" click="deleteData({{ $selectedId }})" />
 </div>
