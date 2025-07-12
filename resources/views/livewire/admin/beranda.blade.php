<div>
    <x-alerts.dispatch-message />

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <!-- Petugas Card -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card border-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-primary">Petugas</h5>
                            <h2 class="mb-0">{{ $totalPetugas }}</h2>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bi bi-people-fill text-primary fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-primary">{{ $totalAktifPetugas }} Aktif</span>
                        <span class="badge bg-secondary">{{ $totalPetugas - $totalAktifPetugas }} Tidak Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Perangkat Card -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card border-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-success">Perangkat</h5>
                            <h2 class="mb-0">{{ $totalPerangkat }}</h2>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bi bi-device-ssd-fill text-success fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-success">{{ $totalPerangkatBaik }} Baik</span>
                        <span class="badge bg-danger">{{ $totalPerangkat - $totalPerangkatBaik }} Rusak</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Insiden Card -->
        <div class="col-md-4 col-sm-6 mb-3">
            <div class="card border-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title text-warning">Insiden</h5>
                            <h2 class="mb-0">{{ $totalInsiden }}</h2>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bi bi-exclamation-triangle-fill text-warning fs-4"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="badge bg-warning">{{ $totalInsidenAktif }} Sedang Berlangsung</span>
                        <span class="badge bg-secondary">{{ $totalInsiden - $totalInsidenAktif }} Selesai</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Incidents -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Insiden Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nama Insiden</th>
                                    <th>Status</th>
                                    <th>Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInsiden as $insiden)
                                    <tr>
                                        <td>{{ $insiden->nama_insiden }}</td>
                                        <td>
                                            @if ($insiden->status === false)
                                                <span class="badge bg-warning">Belum Selesai</span>
                                            @else
                                                <span class="badge bg-success"> Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            @foreach ($insiden->petugasInsiden as $assignment)
                                                <span class="badge bg-primary">{{ $assignment->petugas->nama }}</span>
                                            @endforeach
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada insiden terbaru</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-2">
                        <a href="{{ route('admin.insiden') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Grafik Insiden</h5>
                </div>
                <div class="card-body">
                    <canvas id="logChart" style="height: 400px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

     {{-- <div class="row">
                <h3>LOKASI INSIDEN</h3>
                <div id="map" style="height: 400px; width: 100%;"></div>
            </div> --}}

</div>


@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        .leaflet-control-custom {
            background: white;
            border-radius: 4px;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.65);
            font-family: Arial, sans-serif;
        }

        .leaflet-control-custom .toggle-btn {
            display: flex;
            align-items: center;
            padding: 8px;
            cursor: pointer;
            font-size: 18px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .leaflet-control-custom .layer-list {
            display: none;
            flex-direction: column;
            padding: 5px;
        }

        .leaflet-control-custom .layer-list.active {
            display: flex;
        }

        .leaflet-control-custom .layer-item {
            display: flex;
            align-items: center;
            padding: 5px 10px;
            margin: 2px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        .leaflet-control-custom .layer-item.active {
            background: #e0e0e0;
        }

        .leaflet-control-custom .layer-item:hover {
            background: #f4f4f4;
        }

        .leaflet-control-custom .icon {
            margin-right: 8px;
            font-size: 16px;
        }
        .timeline {
            position: relative;
            padding-left: 20px;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 1.5rem;
        }

        .timeline-item-marker {
            position: absolute;
            left: -6px;
            top: 0;
        }

        .timeline-item-marker-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            border: 2px solid #fff;
        }

        .timeline-item-content {
            padding-left: 20px;
            position: relative;
            top: -3px;
        }

        @media (max-width: 576px) {
            .timeline {
                padding-left: 15px;
            }

            .timeline-item-content {
                padding-left: 15px;
            }

            .timeline-item-marker {
                left: -5px;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js">
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('logChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie', // Set chart type to bar
                data: @json($logChartData),
                options: @json($chartOptions)
            });

        });


        </script>
@endpush

