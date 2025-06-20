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
        <div class="col-md-3 col-sm-6 mb-3">
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
        <div class="col-md-3 col-sm-6 mb-3">
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
        <div class="col-md-3 col-sm-6 mb-3">
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
                                           @if($insiden->status === false)
                                            <span class="badge bg-warning">Belum Selesai</span>
                                           @else
                                            <span class="badge bg-success"> Selesai</span>
                                            
                                           @endif
                                        </td>
                                        <td>
                                            @foreach($insiden->petugasInsiden as $assignment)
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

        <!-- Recent Logs -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        @forelse($recentLogs as $log)
                            <div class="timeline-item">
                                <div class="timeline-item-marker">
                                    <div class="timeline-item-marker-indicator bg-info"></div>
                                </div>
                                <div class="timeline-item-content">
                                    <div class="d-flex justify-content-between">
                                        <strong>{{ $log->petugasInsiden->petugas->nama }}</strong>
                                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">{{ $log->insiden->nama_insiden }}</p>
                                    @if($log->suhu)
                                        <span class="badge bg-danger me-1">Suhu: {{ $log->suhu }}°C</span>
                                    @endif
                                    @if($log->kualitas_udara)
                                        <span class="badge bg-warning">Udara: {{ $log->kualitas_udara }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-center">Tidak ada aktivitas terbaru</p>
                        @endforelse
                    </div>
                    {{-- <div class="text-end mt-2">
                        <a href="{{ route('admin.logs.index') }}" class="btn btn-sm btn-info">Lihat Semua</a>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    <!-- Log Chart Visualization -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Grafik Aktivitas Log Terbaru</h5>
                </div>
                <div class="card-body">
                    <canvas id="logChart" style="height: 400px; width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.1/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('logChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: @json($logChartData),
                options: @json($chartOptions)
            });
        });
    </script>
@endpush

@push('styles')
    <style>
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