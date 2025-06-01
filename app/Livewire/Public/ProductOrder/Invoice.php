<?php

namespace App\Livewire\Public\ProductOrder;

use Livewire\Component;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\Transaction\Transaction\TransactionRepository;

class Invoice extends Component
{

    public $objId;
    public $transaction;

    public function mount()
    {
        $this->transaction = TransactionRepository::findBy([
            ['id', Crypt::decrypt($this->objId)]
        ]);
    }

    public function render()
    {
        return view('livewire.public.product-order.invoice');
    }
}
