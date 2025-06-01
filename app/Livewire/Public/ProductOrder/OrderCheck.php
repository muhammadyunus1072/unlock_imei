<?php

namespace App\Livewire\Public\ProductOrder;

use App\Helpers\Alert;
use Livewire\Component;
use App\Models\MasterData\Voucher;
use Illuminate\Support\Facades\Crypt;
use App\Models\MasterData\PaymentMethod;
use App\Repositories\Transaction\Transaction\TransactionRepository;

class OrderCheck extends Component
{
    public $invoice;
    public $customer_phone;

    public function store()
    {
        $phone = null;
        if($this->customer_phone)
        {
            $phone = preg_replace('/[^\d]/', '', $this->customer_phone);
                if (!preg_match("/^8[0-9]{9,11}$/", $phone) || (strlen($phone) < 9 || strlen($phone) > 11)) {
                    Alert::fail($this, "Gagal", "Format No Telp tidak sesuai,<br>Contoh: +62 8XX-XXXX-XXXX");
                    return;
                }
        }
        $this->dispatch('on-search', [
            'invoice' => $this->invoice,
            'customer_phone' => $phone
        ]);
    }

    public function render()
    {
        return view('livewire.public.product-order.order-check');
    }
}
