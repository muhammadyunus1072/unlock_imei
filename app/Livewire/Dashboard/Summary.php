<?php

namespace App\Livewire\Dashboard;

use App\Repositories\Dashboard\SummaryRepository;
use Livewire\Component;
use App\Traits\Livewire\WithDatatableHeader;

class Summary extends Component
{
    use WithDatatableHeader;

    public function getHeaderData()
    {
        $amountNotVerified = SummaryRepository::amountNotVerified();
        $amountWaitingPayment = SummaryRepository::amountWaitingPayment();
        
        return [
            [
                "col" => 6,
                "name" => "Menunggu Verfikasi",
                "value" => $amountNotVerified
            ],
            [
                "col" => 6,
                "name" => "Menunggu Pembayaran",
                "value" => $amountWaitingPayment
            ],
        ];
    }
}
