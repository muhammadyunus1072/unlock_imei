<?php

namespace App\Services;

use App\Models\MasterData\PaymentMethod;
use App\Models\Transaction\Transaction;
use Carbon\Carbon;
use Xendit\Configuration;
use Xendit\Invoice\InvoiceApi;
use Xendit\Invoice\CreateInvoiceRequest;

class XenditService
{
    public static function createInvoice($transaction)
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));

        $apiInstance = new InvoiceApi();

        $description = "Booking ".config('template.title')." - ".$transaction->transactionDetailSample->product_name." - ".$transaction->transactionDetailSample->studio->name;
        $items = [];
        $subtotal = 0;
        

        foreach($transaction->transactionDetails as $index => $item)
        {
            $items[] = [
                "name" => $item->product_name." ".($index+1).": ".Carbon::parse($item->product_booking_time_time)->format('H:i'),
                "quantity" => 1,
                "price" => $item->product_price,
            ];
            $items[] = [
                "name" => "Background ".($index+1).": ".$item->product_detail_name,
                "quantity" => 1,
                "price" => $item->product_detail_price,
            ];

            $subtotal += $item->product_price;
            $subtotal += $item->product_detail_price;
        }
        $admin_fee =  PaymentMethod::TYPE_FIXED === $transaction->payment_method_type ? $transaction->payment_method_amount : calculateAdminFee($subtotal, $transaction->payment_method_amount);

        $fees[] = [
            "type" => "Admin Fee",
            "value" => (float) $admin_fee
        ];
        if ($transaction->voucher_id !== null) {
            array_push($fees, [
                "type" => "Coupon Discount",
                "value" => (float) $transaction->discount * -1
            ]);
        }
        $create_invoice_request = new CreateInvoiceRequest([
        'external_id' => $transaction->external_id,
        'amount' => $transaction->grand_total,
        'description' => $description,
        'customer' => [
            'given_names' => auth()->user()->username,
            'sure_name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'mobile_number' => "+62".$transaction->customer_phone,
        ],
        'customer_notification_preference' => [
            "invoice_created" => [
              "whatsapp",
              "email",
              "viber"
            ],
            "invoice_reminder" => [
              "whatsapp",
              "email",
              "viber"
            ],
            "invoice_paid" => [
              "whatsapp",
              "email",
              "viber"
            ]
        ],
        'invoice_duration' => 1800,
        'success_redirect_url' => route('public.index'),
        'failure_redirect_url' => route('public.index'),
        'payment_methods' => [$transaction->payment_method_code],
        'currency' => 'IDR',
        'locale' => 'id',
        "items" => $items,
        "fees" => $fees,
        'metadata' => [
            'transaction_number' => $transaction->number,
            'app_name' => config('template.title'),
            'user_id' => $transaction->user_id,
            'note' => 'Transaksi dibuat pada '.$transaction->created_at
        ]
        ]); // \Xendit\Invoice\CreateInvoiceRequest

        try {
            $result = $apiInstance->createInvoice($create_invoice_request);
            return $result;
        } catch (\Xendit\XenditSdkException $e) {
            return $e->getMessage();
        }
    }

    public static function getInvoiceStatus($invoiceId)
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));

        $apiInstance = new InvoiceApi();

        try {
            $invoice = $apiInstance->getInvoiceById($invoiceId);

            if ($invoice['status'] === 'PAID') {
                echo "Payment successful!";
            } elseif ($invoice['status'] === 'EXPIRED') {
                echo "Invoice has expired. Payment is not allowed.";
            }
        } catch (\Xendit\XenditSdkException $e) {
            echo "Error retrieving invoice: " . $e->getMessage();
        }
    }

    public static function expireInvoice($invoiceId)
    {
        Configuration::setXenditKey(env('XENDIT_SECRET_KEY'));

        $apiInstance = new InvoiceApi();

        try {
            $invoice = $apiInstance->expireInvoice($invoiceId);

            if ($invoice['status'] === 'PAID') {
                echo "Payment successful!";
            } elseif ($invoice['status'] === 'EXPIRED') {
                echo "Invoice has expired. Payment is not allowed.";
            }
        } catch (\Xendit\XenditSdkException $e) {
            echo "Error retrieving invoice: " . $e->getMessage();
        }
    }
    
}
