<?php

namespace App\Livewire\Public\ProductOrder;

use Livewire\Component;
use Illuminate\Support\Facades\Crypt;
use App\Repositories\Transaction\Transaction\TransactionRepository;

class Invoice extends Component
{

    public $objId;
    public $transaction;
    public $subtotal;
    public $admin_fee;
    public $discount;
    public $grand_total;

    public function mount()
    {
        $this->transaction = TransactionRepository::findBy([
            ['id', Crypt::decrypt($this->objId)]
        ]);
        $this->calculatedTotal();
    }

    private function calculatedTotal()
    {
        $this->subtotal = count($this->transaction->transactionDetails) * $this->transaction->transactionDetails[0]->price;
        $this->grand_total = $this->subtotal + $this->admin_fee - $this->discount;
    }

    public function render()
    {
        return view('livewire.public.product-order.invoice');
    }
}
