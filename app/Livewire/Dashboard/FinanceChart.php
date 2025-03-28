<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Livewire\Component;
use App\Traits\Livewire\WithChartJs;
use App\Repositories\Report\DashboardRepository;
use App\Repositories\Simrs\SimrsKontraktorRepository;
use Illuminate\Support\Facades\Crypt;

class ChartPiutangPembayaran extends Component
{
    use WithChartJs;

    public $dateStart;
    public $dateEnd;

    public $simrsKontraktorId;
    public $simrsKontraktorIds = [];

    public function onMount()
    {
        $this->dateStart = Carbon::now()->startOfYear()->format("Y-m-d");
        $this->dateEnd = Carbon::now()->endOfYear()->format("Y-m-d");
        $this->simrsKontraktorIds = array_column(SimrsKontraktorRepository::getFilterChoiceByAuthenticatedUser(false), 'id');
    }

    public function getConfig(): array
    {
        return [
            'type' => 'line',
            'options' => [
                'responsive' => true,
                'plugins' => [
                    'legend' => [
                        'position' => 'top',
                    ],
                    'title' => [
                        'display' => true,
                        'text' => 'Grafik Pembayaran dan Transaksi Pasien'
                    ]
                ]
            ]
        ];
    }

    public function getData(): array
    {
        $simrsKontraktorIds = $this->simrsKontraktorId ? [Crypt::decrypt($this->simrsKontraktorId)] : $this->simrsKontraktorIds;

        $dataset_1 = collect(DashboardRepository::getTipePiutang($this->dateStart, $this->dateEnd, $simrsKontraktorIds));
        $dataset_2 = collect(DashboardRepository::getTipePembayaran($this->dateStart, $this->dateEnd, $simrsKontraktorIds));

        $labels_1 = $dataset_1->pluck('periode_laporan');
        $labels_2 = $dataset_2->pluck('periode_laporan');

        $merged_labels = $labels_1->merge($labels_2)->unique()->sort()->values();

        $data_1 = $merged_labels->map(function ($label) use ($dataset_1) {
            return $dataset_1->firstWhere('periode_laporan', $label)['total_nilai'] ?? 0;
        });

        $data_2 = $merged_labels->map(function ($label) use ($dataset_2) {
            $value = $dataset_2->firstWhere('periode_laporan', $label)['total_nilai'] ?? 0;
            return $value ? $value * -1 : 0;
        });

        return [
            'labels' => $merged_labels,
            'datasets' => [
                [
                    'label' => 'Tagihan',
                    'data' => $data_1,
                    'borderColor' => '#0f03fc',
                ],
                [
                    'label' => 'Pembayaran',
                    'data' => $data_2,
                    'borderColor' => '#FF6384',
                ]
            ]
        ];
    }

    public function getView(): string
    {
        return 'livewire.livewire-chart-js';
    }
}
