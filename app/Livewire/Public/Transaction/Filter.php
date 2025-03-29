<?php

namespace App\Livewire\Public\Transaction;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Transaction\Transaction;

class Filter extends Component
{
    public $statuses = [];
    public $status = 'Seluruh';
    public $dateStart;
    public $dateEnd;

    public function mount()
    {
        $this->statuses = Transaction::STATUS_CHOICE;

        $this->dateStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->dateEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function updated()
    {
        $this->dispatch('datatable-add-filter', [
            'status' => $this->status,
            'dateStart' => $this->dateStart,
            'dateEnd' => $this->dateEnd
        ]);
    }

    public function render()
    {
        return view('livewire.public.transaction.filter');
    }
}
