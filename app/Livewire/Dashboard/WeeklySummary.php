<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Livewire\Component;
use App\Traits\Livewire\WithChartJs;
use App\Repositories\Dashboard\SummaryRepository;
use App\Repositories\Report\TransactionReport\TransactionReportRepository;

class WeeklySummary extends Component
{
    use WithChartJs;

    public function onMount()
    {
        $this->canvasId = 'weekly-summary-chart';
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
                        'text' => 'Grafik Transaksi 1 Minggu Terakhir'
                    ]
                ]
            ]
        ];
    }

    public function getData(): array
    {
        $labels = collect([
            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu'
        ]);

        $weeklyData = SummaryRepository::transactionWeekly()->map(function ($item) {
                $item->day = $this->lebels[$item->transaction_day] ?? $item->transaction_day;
                return $item;
            });
       
        $todayEnglish = Carbon::now()->translatedFormat('l');
        $todayIndonesian = $labels->flip()[$todayEnglish]; 
        
        $sortedLabels = $labels->keys()->reject(fn($day) => $day === $todayIndonesian)->push($todayIndonesian);
        
        $data = $sortedLabels->map(function($enDay) use ($weeklyData, $labels) {
            return $weeklyData->where('transaction_day', $enDay)->first()->transaction_amount ?? 0; 
        });
        $indoLabels = $sortedLabels->map(function($day) use ($labels) {
            return $labels[$day];
        });
        dd($data->values());
        return [
            'labels' => $indoLabels,
            'datasets' => [
                [
                    'label' => 'Total Transaksi',
                    'data' => $data->values(),
                    'borderColor' => '#0f03fc',
                ]
            ]
        ];
    }

    public function getView(): string
    {
        return 'livewire.livewire-chart-js';
    }
}
