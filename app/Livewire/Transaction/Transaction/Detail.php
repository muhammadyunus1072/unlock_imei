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

    public $transaction_status_badge;
    public $transaction_status_name;
    public $payment_status_badge;
    public $payment_status_name;
    public $customer_ig;
    public $customer_fb;
    public $customer_ktp_url;
    public $customer_lat;
    public $customer_lng;
    public $subtotal = 0;
    public $admin_fee = 0;


    public function mount()
    {
        if($this->objId)
        {
            $transaction = TransactionRepository::find(Crypt::decrypt($this->objId));
            $social_media = json_decode($transaction->customer_social_media);
            $this->customer_ig = $social_media->instagram;
            $this->customer_fb = $social_media->facebook;
            $this->transaction = $transaction->toArray();
            $this->customer_lat = $transaction->customer_lat;
            $this->customer_lng = $transaction->customer_long;
            $this->transaction_status_badge = $transaction->getTransactionStatusBadge();
            $this->payment_status_badge = $transaction->getPaymentStatusBadge();
            $this->customer_ktp_url = $transaction->customer_ktp_url();
            $this->subtotal = $transaction->transactionDetails->sum(function ($detail) {
                return $detail->product_price + $detail->product_detail_price;
            });
            $this->admin_fee = PaymentMethod::TYPE_PERCENTAGE == $transaction->payment_method_type ? calculatedAdminFee($this->subtotal, $transaction->payment_method_amount) : $transaction->payment_method_amount;

            foreach($transaction->transactionDetails as $index => $item){
                $this->transaction_details[] = [
                    'booking_date' => $item['booking_date'],
                    'imei_url' => generateUrl($item['imei'], FilePathHelper::FILE_CUSTOMER_IMEI),
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
            ['payment_status', Transaction::PAYMENT_STATUS_PENDING]
        ]);
    }

    public function render()
    {
        return view('livewire.transaction.transaction.detail');
    }
}
