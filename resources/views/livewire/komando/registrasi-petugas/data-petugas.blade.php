<div wire:poll.2s class="row g-4">
    @foreach ($petugasInsidenData as $device)
        <div id="deviceItem" class="col-12 mb-4">
            <div class="card bg-black text-white border border-secondary shadow-lg px-4 py-3"
                style="border-radius: 12px;">
                <div class="row align-items-center g-4">
                    <div class="col-md-4 col-12 text-center d-flex flex-column align-items-center">
                        @if ($device['foto'])
                            <img width="100" height="100" src="{{ $device['foto'] }}"
                                alt="Foto {{ $device['nama_petugas'] }}"
                                class="rounded-circle border border-light shadow"
                                style="object-fit: cover; aspect-ratio: 1/1;">
                        @else
                            <img width="100" height="100"
                                src="{{ url('public/komando/assets/img/user/petugas.jpg') }}"
                                alt="Foto {{ $device['nama_petugas'] }}"
                                class="rounded-circle border border-light shadow"
                                style="object-fit: cover; aspect-ratio: 1/1;">
                        @endif
                        <div class="fw-bold fs-5 mt-3">{{ $device['nama_petugas'] }}</div>
                    </div>
                    <div class="col-md-4 col-12 text-center text-md-start">
                        <div class="text-uppercase text-secondary small fw-semibold">Status</div>
                        <div class="mb-3 fw-bold {{ $device['status_color'] }}">{{ $device['status_text'] }}</div>
                        <div class="text-uppercase text-secondary small fw-semibold">Nomor Seri</div>
                        <div class="fs-4 fw-bold text-warning">{{ $device['no_seri'] }}</div>
                    </div>
                    <div class="col-md-4 col-12 text-center text-md-start">
                        <div class="text-uppercase text-secondary small fw-semibold mb-1">Kualitas Udara</div>
                        <div class="mb-2">
                            <span class="fs-4 fw-bold text-warning">{{ $device['kualitas_udara'] }}</span>
                        </div>
                        <div class="text-uppercase text-secondary small fw-semibold mb-1">Suhu</div>
                        <div class="text-danger fs-5 fw-bold">{{ $device['suhu'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
