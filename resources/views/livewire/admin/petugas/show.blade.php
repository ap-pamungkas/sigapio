<div>
    <x-alerts.dispatch-message />
    <div wire:poll.1s class="card card-outline card-info">
        <div class="card-header">
            <h3 class="card-title">Detail Petugas</h3>
            <div class="card-tools">
                <a href="{{ route('admin.petugas') }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
        <div class="card-body">
            @if ($petugas)
                <div class="row mb-3">
                    <div class="col-md-4 text-center">
                        @if ($petugas->foto)
                            <img src="{{ asset('public/storage/' . $petugas->foto) }}" class="img-fluid rounded mb-2"
                                style="max-height: 250px;" alt="Foto Petugas">
                        @else
                            <img src="{{ asset('komando/assets/img/user/petugas.jpg') }}" class="img-fluid rounded mb-2"
                                style="max-height: 250px;" alt="Foto Default">
                        @endif
                        <p class="text-muted">{{ $petugas->nama }}</p>
                    </div>
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tr>
                                <th width="30%">Nama</th>
                                <td>{{ $petugas->nama }}</td>
                            </tr>
                            <tr>
                                <th width="30%">No Telepon</th>
                                <td>{{ $petugas->no_telepon ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Jenis Kelamin</th>
                                <td>{{ ucfirst($petugas->jenis_kelamin) }}</td>
                            </tr>
                            <tr>
                                <th>Tanggal Lahir</th>
                                <td>{{ \Carbon\Carbon::parse($petugas->tgl_lahir)->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Alamat</th>
                                <td>{{ $petugas->alamat }}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    {{ $petugas->status }}
                                    <button class="btn btn-sm btn-warning ms-2"
                                        wire:click="updateStatus({{ $petugas->id }})">
                                        <i class="bi bi-arrow-repeat"></i> Ubah Status
                                    </button>
                                </td>
                            </tr>

                        </table>
                    </div>
                </div>
            @else
                <div class="alert alert-warning">Data petugas tidak ditemukan.</div>
            @endif
        </div>
    </div>
</div>
