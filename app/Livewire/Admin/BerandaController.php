<?php

namespace App\Livewire\Admin;

use App\Models\Insiden;
use App\Models\LogInsiden;
use App\Models\Petugas;
use App\Models\Perangkat;
use App\Traits\DispatchMessage;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Title;
use Livewire\Component;

class BerandaController extends Component
{
    use DispatchMessage;

    #[Title('Admin - Beranda')]

    public $totalPetugas;
    public $totalAktifPetugas;
    public $totalPerangkat;
    public $totalPerangkatBaik;
    public $totalInsiden;
    public $totalInsidenAktif;
    public $recentInsiden;
    public $recentLogs;
    public $logChartData;
    public $chartOptions;
    protected $limit = 5; // Configurable limit for recent data

    public function mount()
    {
        if (session()->has('success')) {
            $this->success(session()->get('success'), 2000);
        }

        try {
            $this->loadStatistics();
            $this->prepareChartData();
        } catch (\Exception $e) {
            Log::error('Error loading dashboard statistics:', ['error' => $e->getMessage()]);

        }
    }

    private function loadStatistics()
    {
        $this->loadPetugasStats();
        $this->loadPerangkatStats();
        $this->loadInsidenStats();
        $this->loadRecentData();
    }

    private function loadPetugasStats()
    {
        $this->totalPetugas = Petugas::count();
        $this->totalAktifPetugas = Petugas::where('status', 'Aktif')->count();
    }

    private function loadPerangkatStats()
    {
        $this->totalPerangkat = Perangkat::count();
        $this->totalPerangkatBaik = Perangkat::where('kondisi', 'Baik')->count();
    }

    private function loadInsidenStats()
    {
        $this->totalInsiden = Insiden::count();
        $this->totalInsidenAktif = Insiden::where('status', false)->count();
    }

    private function loadRecentData()
    {
        // Recent incidents with eager loading
        $this->recentInsiden = Insiden::with([
            'petugasInsiden' => fn($query) => $query->with(['petugas', 'perangkat']),
        ])
            ->latest()
            ->limit($this->limit)
            ->get();

        // Recent logs with eager loading
        $this->recentLogs = LogInsiden::with([
            'insiden:id,nama_insiden,status',
            'petugasInsiden.petugas:id,nama',
        ])
            ->latest()
            ->limit($this->limit)
            ->get();
    }

    private function prepareChartData()
    {
        // Group logs by petugas and time (hourly)
        $logs = LogInsiden::selectRaw(
            'petugas.nama as petugas_nama,
             DATE_FORMAT(log_insiden.created_at, "%Y-%m-%d %H:00:00") as time,
             COUNT(*) as count'
        )
        ->join('petugas_insiden', 'log_insiden.petugas_insiden_id', '=', 'petugas_insiden.id')
        ->join('petugas', 'petugas_insiden.petugas_id', '=', 'petugas.id')
        ->groupBy('petugas_nama', 'time')
        ->orderBy('time')
        ->limit(50) // Limit to prevent excessive data
        ->get();

        $series = [];
        $petugasNames = $logs->pluck('petugas_nama')->unique();
        $times = $logs->pluck('time')->unique()->sort();

        foreach ($petugasNames as $nama) {
            $data = $times->map(function ($time) use ($logs, $nama) {
                $log = $logs->where('petugas_nama', $nama)->where('time', $time)->first();
                return $log ? $log->count : 0;
            })->toArray();

            $series[] = [
                'label' => $nama,
                'data' => $data,
                'borderColor' => $this->getRandomColor(),
                'fill' => false,
            ];
        }

        $this->logChartData = [
            'labels' => $times->toArray(),
            'datasets' => $series,
        ];

        $this->chartOptions = [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'type' => 'time',
                    'time' => [
                        'unit' => 'hour',
                        'displayFormats' => [
                            'hour' => 'MMM d, HH:mm',
                        ],
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Waktu Aktivitas',
                    ],
                ],
                'y' => [
                    'beginAtZero' => true,
                    'title' => [
                        'display' => true,
                        'text' => 'Jumlah Aktivitas',
                    ],
                ],
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
                'tooltip' => [
                    'mode' => 'index',
                    'intersect' => false,
                ],
            ],
        ];
    }

    private function getRandomColor()
    {
        // Array of distinct colors for chart lines
        static $colors = [
            '#0066cc', '#ff4b5b', '#28a745', '#ffcc00', '#9933ff',
            '#00cc99', '#ff6633', '#669999', '#cc33cc', '#3399ff',
        ];
        static $index = 0;
        $color = $colors[$index % count($colors)];
        $index++;
        return $color;
    }

    public function render()
    {
        return view('livewire.admin.beranda');
    }
}
