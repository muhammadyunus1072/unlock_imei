<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Dashboard\SummaryRepository;
use App\Traits\Livewire\WithDatatableHeader;

class YearlySummary extends Component
{
    use WithDatatableHeader;

    private function getHeaderData()
    {
        $transaction = SummaryRepository::transactionYearly();
        $transactionAmount = $transaction->count();
        $transactionValue = $transaction->sum('grand_total');
        return [
            [
                "col" => 6,
                "name" => "Total Transaksi",
                "value" => $transactionAmount
            ],
            [
                "col" => 6,
                "name" => "Nilai Transaksi",
                "value" => $transactionValue
            ],
        ];
    }
}
