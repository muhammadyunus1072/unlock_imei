<?php

namespace App\Livewire\Public\ProductOrder;

use Livewire\Component;
use Illuminate\Support\Facades\Crypt;
use App\Models\MasterData\PaymentMethod;
use App\Models\MasterData\Voucher;
use App\Models\Transaction\Transaction;
use App\Repositories\Transaction\Transaction\TransactionRepository;
use Livewire\Attributes\On;

class Invoice extends Component
{
    public $objId;
    public $transaction;
    public $subtotal;
    public $discount;
    public $grand_total;
    public $amount_due;

    public function mount()
    {
        $this->getData();
    }

    private function getData()
    {
        if (!$this->objId) {
            return;
        }
        $this->transaction = TransactionRepository::findBy([
            ['id', simple_decrypt($this->objId)]
        ]);
        $this->calculatedTotal();
    }

    #[On('on-search')]
    public function onSearch($data)
    {
        if(!isset($data['invoice']) && !isset($data['customer_phone']))
        {
            $this->objId = null;
            return;
        }
        $transaction = Transaction::when($data['invoice'], function($query) use($data)
        {
            $query->where('number', '=', $data['invoice']);
        })
        ->when($data['customer_phone'], function($query) use($data)
        {
            $query->where('customer_phone', '=', $data['customer_phone']);
        })->first();

        if($transaction)
        {
            $this->objId = simple_decrypt($transaction->id);
            $this->getData();
        }else{
            $this->objId = null;
            return;
        }
    }

    private function calculatedTotal()
    {
        $this->subtotal = count($this->transaction->transactionDetails) * $this->transaction->transactionDetails[0]->product_price;
        
        $this->amount_due = $this->transaction->amount_due;
        // DISCOUNT
        $this->discount = $this->transaction->discount;

        $this->grand_total = $this->subtotal - $this->discount;
    }

    public function render()
    {
        return view('livewire.public.product-order.invoice');
    }
}
