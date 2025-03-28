<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Livewire\Component;
use App\Traits\Livewire\WithChartJs;
use App\Repositories\Dashboard\SummaryRepository;

class MonthlySummary extends Component
{
    use WithChartJs;
    
    public function onMount()
    {
        $this->canvasId = 'monthly-summary-chart';
    }

    public function getConfig(): array
    {
        return [
            'type' => 'line',
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'position' => 'bottom',
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Grafik Transaksi 1 Bulan Terakhir'
                    ]
                ]
            ]
        ];
    }

    public function getData(): array
    {
        $monthlyData = SummaryRepository::transactionMonthly();
        $totalDays = now()->daysInMonth;

        // Buat daftar tanggal dari 1 hingga total hari dalam bulan
        $dates = collect(range(1, $totalDays))->map(function ($day) {
            return str_pad($day, 2, '0', STR_PAD_LEFT)."-".str_pad(now()->month, 2, '0', STR_PAD_LEFT); // Format jadi 01, 02, ..., 31
        });

        // Format tanggal transaksi untuk pencocokan
        $transactionData = $monthlyData->mapWithKeys(function ($item) {
            return [Carbon::parse($item->transaction_date)->format('d-m') => $item->transaction_amount];
        });

        // Buat data transaksi berdasarkan urutan tanggal
        $data = $dates->map(fn($day) => $transactionData[$day] ?? 0);

        // Urutkan agar hari ini ada di akhir
        $today = now()->format('d-m');
        $sortedDates = $dates->reject(fn($day) => $day === $today)->push($today);
        $sortedData = $sortedDates->map(fn($day) => $transactionData[$day] ?? 0);

        // dd($sortedData);
        // **Hasil Akhir untuk Chart.js**
        return [
            'labels' => $sortedDates->values(),
            'datasets' => [
                [
                    'label' => 'Total Transaksi',
                    'data' => $sortedData->values(),
                    'borderColor' => '#fb70aa',
                ]
            ]
        ];
    }

    public function getView(): string
    {
        return 'livewire.livewire-chart-js';
    }
}
