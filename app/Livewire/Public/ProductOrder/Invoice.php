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
    public $admin_fee;
    public $discount;
    public $grand_total;

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
            ['id', Crypt::decrypt($this->objId)]
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
            $this->objId = Crypt::encrypt($transaction->id);
            $this->getData();
        }else{
            $this->objId = null;
            return;
        }
    }

    private function calculatedTotal()
    {
        $this->subtotal = count($this->transaction->transactionDetails) * $this->transaction->transactionDetails[0]->product_price;
        // ADMIN FEE
        if($this->transaction->payment_method_fee_type === PaymentMethod::TYPE_PERCENTAGE)
        {
        $this->admin_fee = calculatedAdminFee($this->subtotal, $this->transaction->payment_method_fee_amount);
        }else{
        $this->admin_fee = $this->transaction->payment_method_fee_amount;
        }
        
        // DISCOUNT
        if($this->transaction->voucher_type === Voucher::TYPE_PERCENTAGE)
        {
        $this->discount = calculatedAdminFee($this->subtotal, $this->transaction->voucher_amount);
        }else{
        $this->discount = $this->transaction->voucher_amount;
        }

        $this->grand_total = $this->subtotal + $this->admin_fee - $this->discount;
    }

    public function render()
    {
        return view('livewire.public.product-order.invoice');
    }
}
