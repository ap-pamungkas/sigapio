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


    protected $petugasInsidenRepository;
    protected $limit = 5; // Configurable limit for recent data

    public function mount()
    {
        if (session()->has('success')) {
            $this->success(session()->get('success'), 5000);
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
    // Group incidents by year and count occurrences
    $incidents = Insiden::selectRaw('YEAR(created_at) as year, COUNT(*) as count')
        ->whereNotNull('created_at') // Ensure no null timestamps
        ->groupBy('year')
        ->orderBy('year') // Sort by year ascending
        ->get();

    // Prepare labels and data for the chart
    $labels = $incidents->pluck('year')->map(function ($year) {
        return (string)$year; // Convert to string for categorical axis
    })->toArray();
    $data = $incidents->pluck('count')->toArray();

    // Generate a single color using getRandomColor
    $color = $this->getRandomColor(); // Use one color for the line

    // Set chart data
    $this->logChartData = [
        'labels' => $labels,
        'datasets' => [
            [
                'label' => 'Jumlah Insiden',
                'data' => $data,
                'borderColor' => $color,
                'backgroundColor' => $color, // Used for points in line chart
                'fill' => false, // No fill for a standard line chart
                'tension' => 0.4, // Slight curve for smoother lines
            ],
        ],
    ];

    // Set chart options
    $this->chartOptions = [
        'responsive' => true,
        'maintainAspectRatio' => false,
        'scales' => [
            'x' => [
                'title' => [
                    'display' => true,
                    'text' => 'Tahun',
                ],
            ],
            'y' => [
                'beginAtZero' => true,
                'title' => [
                    'display' => true,
                    'text' => 'Jumlah Insiden',
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
