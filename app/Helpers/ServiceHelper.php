<?php 

namespace App\Helpers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;

class ServiceHelper
{
    public static function generateOrderConfirmationMessage($transaction)
    {
        $message = [
            "
    *Konfirmasi Pesanan*  
    *Pesanan   : ".$transaction->number."*
    *Tanggal    : ".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
    *Nama       : ".$transaction->customer_name."*  
    *No WA     : 62".$transaction->customer_phone."*  
    *Item         : ".$transaction->transactionDetails[0]->product_name."*  
    *Kuantitas : ".numberFormat($transaction->transactionDetails->count())." IMEI*  
    *Total        : Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

    Konfirmasi pembayaran dapat dilakukan melalui link berikut:
    ".route('transaction.edit', ['id' => simple_encrypt($transaction->id)]),
        ];
        return $message[0];
        // return $message[rand(0, count($message) - 1)];
    }
    public static function generateWhatsappPaymentMessage($transaction)
    {
        $message = [
            "*Konfirmasi Pembayaran*  
    *Pesanan   : ".$transaction->number."*
    *Tanggal    : ".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
    *Nama       : ".$transaction->customer_name."*  
    *No WA     : 62".$transaction->customer_phone."*  
    *Item         : ".$transaction->transactionDetails[0]->product_name."*  
    *Kuantitas : ".numberFormat($transaction->transactionDetails->count())." IMEI*  
    *Total        : Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

    Pesanan anda sudah kami aktifkan, silahkan melakukan pembayaran melalui link berikut:
    ".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),
        ];
        return $message[0];
        // return $message[rand(0, count($message) - 1)];
    }
    
    public static function kirimWhatsapp($phone, $message)
    {
        
        $url = env('ADSMEDIA_REGULER_URL', null);

        $apikey = env('ADSMEDIA_API_KEY', null); // apikey , dapatkan di menu api information
        $deviceid = env('ADSMEDIA_DEVICE_ID'); //deviceid dapatkan di menu device
        $phone = "62".$phone; // 6281xxxxxxx
        $doc = "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf";
        $img = "https://fastly.picsum.photos/id/295/200/300.jpg?hmac=b6Ets6Bu47pFHcU4UK7lI6xYkfy48orifVzWeHAe0zM";

        $payload = [
                "deviceid" => $deviceid,
                "phone" => $phone,
                "message" => $message,
                "document" => $doc,
                "image" => $img,
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER,
        array(
            "Authorization:Bearer $apikey",
            "Content-Type: application/json"
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($payload) );
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        curl_close($curl);
        dd($result);
        logger($result);
        
        // $response = curl_exec($curl);
        // $errors = curl_error($curl);
        // $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        // logger(json_decode($response)->status);
        // return [
        //     'code' => $code,
        //     'response' => $response,
        //     'errors' => $errors,
        // ];
    }
}