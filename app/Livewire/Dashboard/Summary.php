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
        $transactionCount = SummaryRepository::transactionCount();
        $bookingActive = SummaryRepository::bookingActive();
        $bookingPending = SummaryRepository::bookingPending();
        $bookingPaid = SummaryRepository::bookingPaid();
        $bookingExpired = SummaryRepository::bookingExpired();

        return [
            [
                "col" => 6,
                "name" => "Total Transaksi",
                "value" => $transactionCount
            ],
            [
                "col" => 6,
                "name" => "Booking Aktif",
                "value" => $bookingActive
            ],
            [
                "col" => 4,
                "name" => "Status PENDING",
                "value" => $bookingPending
            ],
            [
                "col" => 4,
                "name" => "Status PAID",
                "value" => $bookingPaid
            ],
            [
                "col" => 4,
                "name" => "Status EXPIRED",
                "value" => $bookingExpired
            ],
        ];
    }
}
