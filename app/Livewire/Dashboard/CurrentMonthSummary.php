<?php

namespace App\Livewire\Dashboard;

use App\Repositories\Dashboard\SummaryRepository;
use Livewire\Component;
use App\Traits\Livewire\WithDatatableHeader;

class CurrentMonthSummary extends Component
{
    use WithDatatableHeader;

    public function getHeaderData()
    {
        $transactionCurrentMonth = SummaryRepository::transactionCurrentMonth();
        $currentMonthAmount = $transactionCurrentMonth->sum('qty');
        $currentMonthValue = collect($transactionCurrentMonth)->sum('total_amount');
        
        return [
            [
                "col" => 6,
                "name" => "Jumlah Transaksi IMEI",
                "value" => $currentMonthAmount
            ],
            [
                "col" => 6,
                "name" => "Nilai Transaksi IMEI",
                "value" => $currentMonthValue
            ],
        ];
    }
}
