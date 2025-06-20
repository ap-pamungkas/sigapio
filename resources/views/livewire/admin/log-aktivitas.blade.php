<div>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Log Aktivitas</h5>
                    <div class="d-flex gap-2">
                        <input type="text" class="form-control form-control-sm" placeholder="Cari deskripsi atau petugas..." wire:model.live.debounce.300ms="search" style="max-width: 200px;">
                        <select class="form-select form-select-sm" wire:model.live="logNameFilter" style="max-width: 150px;">
                            <option value="">Semua Log Name</option>
                            @foreach($logNames as $logName)
                                <option value="{{ $logName }}">{{ $logName }}</option>
                            @endforeach
                        </select>
                        <select class="form-select form-select-sm" wire:model.live="eventFilter" style="max-width: 150px;">
                            <option value="">Semua Event</option>
                            @foreach($events as $event)
                                <option value="{{ $event }}">{{ $event }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>User</th>
                                    <th>Deskripsi</th>
                                    <th>Event</th>
                                    <th>Subjek</th>
                                    <th>Log Name</th>
                                    <th>Properti</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activities as $activity)
                                    <tr>
                                        <td>{{ $activity->created_at->format('d M Y H:i') }}</td>
                                        <td>
                                            @if($activity->causer)
                                                <span class="badge bg-primary">{{ $activity->causer->nama }}</span>
                                            @else
                                                <span class="badge bg-secondary">Tidak Diketahui</span>
                                            @endif
                                        </td>
                                        <td>{{ $activity->description }}</td>
                                        <td>
                                            @if($activity->event)
                                                <span class="badge bg-info">{{ $activity->event }}</span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($activity->subject)
                                                {{ class_basename($activity->subject_type) }} (ID: {{ $activity->subject_id }})
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($activity->log_name)
                                                <span class="badge bg-warning">{{ $activity->log_name }}</span>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($activity->properties)
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#propertiesModal{{ $activity->id }}">
                                                    Lihat Properti
                                                </button>
                                                <!-- Modal for Properties -->
                                                <div class="modal fade" id="propertiesModal{{ $activity->id }}" tabindex="-1" aria-labelledby="propertiesModalLabel{{ $activity->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="propertiesModalLabel{{ $activity->id }}">Detail Properti</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <pre>{{ json_encode($activity->properties->toArray(), JSON_PRETTY_PRINT) }}</pre>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="badge bg-secondary">N/A</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada log aktivitas ditemukan</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        {{ $activities->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .table th, .table td {
            vertical-align: middle;
        }
        .badge {
            font-size: 0.85rem;
        }
        pre {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }
        @media (max-width: 576px) {
            .card-header .d-flex {
                flex-direction: column;
                gap: 0.5rem;
            }
            .card-header select, .card-header input {
                max-width: 100% !important;
            }
        }
    </style>
@endpush
