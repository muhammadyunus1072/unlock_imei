<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Dashboard\SummaryRepository;
use App\Traits\Livewire\WithDatatableHeader;

class ByMonthSummary extends Component
{
    use WithDatatableHeader;

    private function getHeaderData()
    {
        $mnth = SummaryRepository::transactionByMonth()->toArray();
        
    }
}
