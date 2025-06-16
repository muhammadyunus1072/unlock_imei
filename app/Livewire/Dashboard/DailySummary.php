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
        $transactionToday = SummaryRepository::transactionToday();
        $this->todayAmount = $transactionToday->sum('qty');
        $this->todayValue = collect($transactionToday)->sum('total_amount');
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
