<?php

namespace App\Helpers;

use Midtrans\Snap;
use Midtrans\Config;
use Midtrans\CoreApi;
use Illuminate\Support\Facades\Http;

class MidtransPayment
{

    public static function checkPaymentStatus()
    {
        $orderId = 'e5d01912-2017-47bb-89be-2d1c94eadda5';
        $serverKey = config('midtrans.server_key');
        // $midtransUrl = "https://api.midtrans.com/v2/{$orderId}/status";
        $midtransUrl = "https://api.sandbox.midtrans.com/v2/{$orderId}/status";

        $response = Http::withBasicAuth($serverKey, '')
                        ->get($midtransUrl);

        return $response->json();
    }

    public static function getSnapToken($transaction_id, $gross_amount, $itemDetails, $customerDetails)
    {
        try {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = array(
                'transaction_details' => [
                    'order_id' => $transaction_id,
                    'gross_amount' => $gross_amount,
                ],
                'item_details' => $itemDetails,
                'customer_details' => $customerDetails,
            );

            $snapToken = Snap::getSnapToken($params);
            return $snapToken;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public static function getQrisApi($transaction_id, $gross_amount, $itemDetails, $customerDetails)
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Payment parameters
        $params = [
            'payment_type' => 'gopay',
            'transaction_details' => [
                'order_id' => $transaction_id."PASDSD",
                'gross_amount' => $gross_amount, // Change to your amount
            ],
            'item_details' => $itemDetails,
            'customer_details' => $customerDetails,
        ];
        
        try {
            $qrisResponse = CoreApi::charge($params);
            return $qrisResponse; // QR Code URL
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
