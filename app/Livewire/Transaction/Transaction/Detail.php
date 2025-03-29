<?php

namespace App\Livewire\Transaction\Transaction;

use Livewire\Component;
use App\Helpers\FilePathHelper;
use Illuminate\Support\Facades\Crypt;
use App\Models\Transaction\Transaction;
use App\Models\MasterData\PaymentMethod;
use App\Repositories\Transaction\Transaction\TransactionRepository;

class Detail extends Component
{
    
    public $objId;

    // Transaction Information
    public $transaction = [];
    public $transaction_details = [];

    public $status_badge;
    public $status_name;
    public $subtotal = 0;
    public $admin_fee = 0;


    public function mount()
    {
        if($this->objId)
        {
            $transaction = TransactionRepository::find(Crypt::decrypt($this->objId));

            $this->transaction = $transaction->toArray();
            $this->status_badge = $transaction->getStatusBadge();
            $this->subtotal = $transaction->transactionDetails->sum(function ($detail) {
                return $detail->product_price + $detail->product_detail_price;
            });
            $this->admin_fee = PaymentMethod::TYPE_PERCENTAGE ? calculatedAdminFee($this->subtotal, $transaction->payment_method_amount) : $transaction->payment_method_amount;

            foreach($transaction->transactionDetails as $index => $item){
                $this->transaction_details[] = [
                    'booking_date' => $item['booking_date'],
                    'product_detail_image_url' => generateUrl($item['product_detail_image'], FilePathHelper::FILE_PRODUCT_DETAIL_IMAGE),
                    'studio_name' => $item->studio->name,
                    'product_name' => $item['product_name'],
                    'product_price' => $item['product_price'],
                    'product_detail_name' => $item['product_detail_name'],
                    'product_detail_price' => $item['product_detail_price'],
                    'product_booking_time_time' => $item['product_booking_time_time'],
                ];
            }
        }
    }

    public function deleteExpired() {
        TransactionRepository::forceDeleteBy([
            ['status', Transaction::STATUS_PENDING]
        ]);
    }

    public function render()
    {
        return view('livewire.transaction.transaction.detail');
    }
}
