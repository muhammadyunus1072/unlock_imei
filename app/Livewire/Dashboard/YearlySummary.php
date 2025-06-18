<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Settings\SettingFinance;
use App\Repositories\Dashboard\SummaryRepository;
use App\Repositories\Core\Setting\SettingRepository;

class YearlySummary extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $web = 10_000;
    public $notification = 29000;

    public function mount()
    {
        $setting = SettingRepository::findBy(whereClause: [['name', SettingFinance::NAME]]);
        
        $settings = json_decode($setting->setting);
        $this->web = $settings->web;
        $this->notification = $settings->adsmedia;
    }

    private function getTransactionMonthly()
    {
        return SummaryRepository::transactionByMonth();
    }

    public function render()
    {
        return view('livewire.dashboard.yearly-summary', [
            'data' => $this->getTransactionMonthly()->paginate(5),
        ]);
    }
}
