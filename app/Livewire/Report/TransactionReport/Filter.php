<?php

namespace App\Livewire\Report\TransactionReport;

use App\Helpers\Alert;
use Livewire\Component;
use Livewire\Attributes\On;
use App\Permissions\PermissionHelper;
use App\Permissions\AccessTransaction;
use App\Models\Transaction\Transaction;
use App\Repositories\Account\UserRepository;
use App\Repositories\Report\TransactionReport\TransactionReportRepository;
use App\Repositories\Transaction\Transaction\TransactionRepository;

class Filter extends Component
{
    public $dateChoice = [];
    public $dateType;

    public function mount()
    {
        $this->dateChoice = TransactionReportRepository::DATE_TYPE_CHOICE;
        $this->dateType = TransactionReportRepository::DATE_TYPE_DAILY;
    }

    public function updated()
    {
        $this->dispatch('datatable-add-filter', [
            'dateType' => $this->dateType
        ]);
    }
    
    public function render()
    {
        return view('livewire.report.transaction-report.filter');
    }
}
