<?php

namespace App\Traits\Livewire;

use App\Jobs\Logistic\TransactionStock\CreateUpdateTransactionStockJob;
use App\Jobs\Logistic\TransactionStock\DeleteTransactionStockJob;
use App\Models\Logistic\Transaction\TransactionStock\TransactionStock;
use Carbon\Carbon;

trait WithWhatsapp
{
    public function generateOrderConfirmationMessage($transaction)
    {
        $message = [
            "*Konfirmasi Pesanan*  
        *Pesanan   : ".$transaction->number."*
        *Tanggal   : ".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
        *Nama      : ".$transaction->customer_name."*  
        *No WA     : 62".$transaction->customer_phone."*  
        *Item      : ".$transaction->transactionDetails[0]->product_name."*  
        *Kuantitas : ".numberFormat($transaction->transactionDetails->count())." IMEI*  
        *Total     : Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  
",
        ];
        return $message;
        return $message[rand(0, count($message) - 1)];
    }
}
