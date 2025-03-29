<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Repositories\Dashboard\SummaryRepository;

class DailySummary extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $todayAmount;
    public $todayValue;
    public function mount()
    {
        $transactionToday = collect(SummaryRepository::transactionToday());
        $this->todayAmount = $transactionToday->count();
        $this->todayValue = $transactionToday->sum('grand_total');
    }

    private function getTransactionDaily()
    {
        return SummaryRepository::transactionDaily();
    }

    public function render()
    {
        return view('livewire.dashboard.daily-summary', [
            'data' => $this->getTransactionDaily()->paginate(10),
        ]);
    }
}
