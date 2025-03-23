<?php

namespace App\Livewire\Public\Transaction;

use App\Models\Transaction\Transaction;
use Livewire\Component;

class Filter extends Component
{
    public $statuses = [];
    public $status = 'Seluruh';

    public function mount()
    {
        $this->statuses = Transaction::STATUS_CHOICE;
    }

    public function updated()
    {
        $this->dispatch('datatable-add-filter', [
            'status' => $this->status
        ]);
    }

    public function render()
    {
        return view('livewire.public.transaction.filter');
    }
}
