<div>
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <div class="row">
                    <div class="col-md-6">
                        <h1 class="text-theme">Insiden {{ $insiden->nama_insiden }}</h1>
                    </div>
                    <div class="col-md-6 ">
                        <div class="float-end">
                            <a href="{{ route('komando.insiden.tracking-petugas', $insiden->id) }}" class="btn btn-success mx-2">
                                <strong>PANTAU PETUGAS</strong>
                            </a>
                            <button class="btn btn-success mx-2"  data-bs-toggle="modal" data-bs-target="#modalSelesai">
                                <strong>SELESAIKAN TUGAS</strong>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr>
    </div>

    <!-- Modal Selesai -->
    <div class="modal fade" id="modalSelesai" tabindex="-1" aria-labelledby="modalSelesaiLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSelesaiLabel">Konfirmasi Selesai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Apakah Anda yakin ingin menyelesaikan tugas ini?
                        <br>
                        Tindakan ini tidak dapat dibatalkan.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" wire:click="finalizeTask">Ya, Selesaikan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-2">
        <!-- BEGIN col-4 -->
        <div class="col-xl-6 col-lg-6">
            <div class="card h-100">
                <div class="card-header mb-3">
                    <span class="text-theme fw-500 fs-14px">REGISTRASI PETUGAS</span>
                    <div class="float-end">
                        <a href="#" data-toggle="card-collapse" class="btn">
                            <i class="fas fa-minus"></i>
                        </a>
                        <a href="#" data-toggle="card-expand" class="btn">
                            <i class="fas fa-expand"></i>
                        </a>
                        <a href="#" data-toggle="card-remove" class="btn">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body" wire:ignore.self>
                    @if (session()->has('success'))
                        <div class="alert alert-success mb-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger mb-3">
                            {{ session('error') }}
                        </div>
                    @endif
                    <hr>
                    <form wire:submit.prevent="confirm">
                        <div class="mb-3">
                            <x-forms.select model="selectedDevice" label="NO SERI PERANGKAT" placeholder="Pilih Perangkat">
                                <option value="">Pilih Perangkat</option>
                                @foreach ($devicesList as $id => $device)
                                    <option value="{{ $id }}">{{ $device }}</option>
                                @endforeach
                            </x-forms.select>
                        </div>
                        <div class="mb-3">
                            <x-forms.select model="selectedPetugas" label="PETUGAS" placeholder="Pilih Petugas">
                                <option value="">Pilih Petugas</option>
                                @foreach ($petugasList as $id => $petugas)
                                    <option value="{{ $id }}">{{ $petugas }}</option>
                                @endforeach
                            </x-forms.select>
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary float-end">Konfirmasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- END col-4 -->
        <!-- BEGIN col-8 -->
        <div class="col-xl-6 col-lg-6">
            <div id="cardPetugas" class="card h-100 ">
                <div class="card-header mb-3">
                    <span class="text-theme fw-500 fs-14px">DATA PETUGAS</span>
                    <div class="float-end">
                        <a href="#" data-toggle="card-collapse" class="btn">
                            <i class="fas fa-minus"></i>
                        </a>
                        <a href="#" data-toggle="card-expand" class="btn">
                            <i class="fas fa-expand"></i>
                        </a>
                        <a href="#" data-toggle="card-remove" class="btn">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="position-relative">
                        <hr>
                        <livewire:komando.registrasi-petugas.data-petugas :insiden-id="$insiden->id" />
                    </div>
                </div>
            </div>
        </div>
        <!-- END col-8 -->
    </div>
    <!-- END col-4 -->
</div>

@livewireScripts

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cardPetugas = document.getElementById('cardPetugas');
            const deviceItems = document.querySelectorAll('#deviceItem');
            const expandButton = cardPetugas.querySelector('[data-toggle="card-expand"]');
            expandButton.addEventListener('click', function(e) {
                e.preventDefault();
                cardPetugas.classList.toggle('card-expand');
                deviceItems.forEach(item => {
                    console.log(cardPetugas.classList.contains('card-expand'));
                    if (cardPetugas.classList.contains('card-expand')) {
                        item.classList.remove('col-4');
                        item.classList.add('col-12');
                    } else {
                        item.classList.remove('col-12');
                        item.classList.add('col-4');
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('livewire:load', function() {
            // Inisialisasi TomSelect
            const select = new TomSelect('#device-select', {
                placeholder: 'Search...',
                allowEmptyOption: true,
                searchField: ['text'],
                sortField: {
                    field: 'text',
                    direction: 'asc'
                },
                onChange: function(value) {
                    @this.set('selectedDevice', value);
                }
            });

            // Perbarui TomSelect saat data Livewire berubah
            Livewire.on('devicesListUpdated', () => {
                select.clearOptions();
                @foreach ($devicesList as $id => $device)
                    select.addOption({
                        value: '{{ $id }}',
                        text: '{{ $device }}'
                    });
                @endforeach
                select.refreshOptions();
            });
        });
    </script>
@endpush
</div>
