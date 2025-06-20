<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use App\Traits\DispatchMessage;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;

class LogAktivitasController extends Component
{
    use WithPagination, DispatchMessage;

    public $perPage = 10;
    public $search = '';
    public $logNameFilter = '';
    public $eventFilter = '';

    #[Title("Log Aktivitas")]
    protected $paginationTheme = 'bootstrap';

    public function mount()
    {
        if (session()->has('success')) {
            $this->success(session()->get('success'), 2000);
        }
    }

    public function render()
    {
        try {
            $activities = Activity::query()
                ->when($this->search, function ($query) {
                    $query->where('description', 'like', '%' . $this->search . '%')
                          ->orWhereHas('causer', function ($q) {
                              $q->where('nama', 'like', '%' . $this->search . '%');
                          });
                })
                ->when($this->logNameFilter, function ($query) {
                    $query->where('log_name', $this->logNameFilter);
                })
                ->when($this->eventFilter, function ($query) {
                    $query->where('event', $this->eventFilter);
                })
                ->with(['causer' => function ($query) {
                    $query->select('id', 'nama'); // Assuming causer is Petugas model
                }, 'subject'])
                ->latest()
                ->paginate($this->perPage);

            // Fetch distinct log_names and events for filters
            $logNames = Activity::select('log_name')
                ->distinct()
                ->whereNotNull('log_name')
                ->pluck('log_name')
                ->toArray();
            $events = Activity::select('event')
                ->distinct()
                ->whereNotNull('event')
                ->pluck('event')
                ->toArray();

            return view('livewire.admin.log-aktivitas', [
                'activities' => $activities,
                'logNames' => $logNames,
                'events' => $events,
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading activity logs:', ['error' => $e->getMessage()]);
            $this->error('Gagal memuat data log aktivitas.', 2000);
            return view('livewire.admin.log-aktivitas', [
                'activities' => [],
                'logNames' => [],
                'events' => [],
            ]);
        }
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedLogNameFilter()
    {
        $this->resetPage();
    }

    public function updatedEventFilter()
    {
        $this->resetPage();
    }
}