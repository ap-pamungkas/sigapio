

<div class="container py-4">
    <x-alerts.dispatch-message />

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    {{-- Ringkasan --}}
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-white">
                <div class="card-header">
                    <h5 class="card-title">🟠 Insiden Aktif</h5>

                </div>
                <div class="card-body">
                    <h2 class="card-text">{{ $insidenAktif->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white ">
                <div class="card-header">
                    <h5 class="card-title">✅ Insiden Selesai</h5>

                </div>
                <div class="card-body">
                    <h2 class="card-text">{{ $insidenSelesai->count() }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-white ">
                <div class="card-header">
                    <h5 class="card-title">👨‍🚒 Petugas Saat Ini</h5>
                </div>
                <div class="card-body">
                    <h2 class="card-text">{{ $logPetugas->count() }}</h2>
                </div>
            </div>
        </div>
    </div>

    {{-- Daftar Insiden Aktif --}}
    <div class="card mb-4">
        <div class="card-header text-white">
            🔥 Insiden Sedang Berlangsung
        </div>
        <div class="card-body">
            @forelse ($insidenAktif as $insiden)
                <div class="mb-3 border-bottom pb-2">
                    <h5>{{ $insiden->nama_insiden }}</h5>
                    <p class="mb-1">{{ $insiden->keterangan }}</p>
                    <small>📍 Lokasi: ({{ $insiden->latitude }}, {{ $insiden->longitude }})</small><br>
                    <small>👨‍🚒 Petugas: {{ $insiden->petugas_insiden_count }}</small>
                </div>
            @empty
                <p class="text-muted">Tidak ada insiden aktif saat ini.</p>
            @endforelse
        </div>
    </div>

    {{-- Peta Lokasi --}}
    <div class="card">
        <div class="card-header  text-white">
            🗺️ Peta Lokasi Insiden & Petugas

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
            <livewire:komando.map wire:poll.3s />
        </div>
    </div>
</div>


