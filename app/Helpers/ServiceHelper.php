<?php 

namespace App\Helpers;

use App\Repositories\Service\SendWhatsapp\SendWhatsappRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Settings\SettingSendWhatsapp;
use Illuminate\Support\Facades\Crypt;

class ServiceHelper
{
    public static function generateOrderVerificationMessage($transaction)
    {
        $message = [
        "
*Verfikasi Pesanan*  
*Pesanan   : ".$transaction->number."*
*Tanggal    : ".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
*Nama       : ".$transaction->customer_name."*  
*No WA     : 62".$transaction->customer_phone."*  
*Item         : ".$transaction->transactionDetails[0]->product_name."*  
*Kuantitas : ".numberFormat($transaction->transactionDetails->count())." IMEI*  
*Total        : Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Verifikasi Pesanan dapat dilakukan melalui link berikut:
".route('transaction.edit', ['id' => simple_encrypt($transaction->id)]),
        ];
        return $message[0];
        // return $message[rand(0, count($message) - 1)];
    }
    public static function generateAwaitingPaymentMessage($transaction)
    {
        $message = [
        "
*IMEI Sudah Aktif, Menunggu Pembayaran*  
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
    
    public static function send($data, $settings)
    {

        $url = $settings->{SettingSendWhatsapp::ADSMEDIA_URL};

        $apikey =  $settings->{SettingSendWhatsapp::ADSMEDIA_API_KEY}; // apikey , dapatkan di menu api information
        $deviceid = $settings->{SettingSendWhatsapp::ADSMEDIA_DEVICE_ID}; //deviceid dapatkan di menu device
        $phone = "62".$data->phone; // 6281xxxxxxx
        // $doc = "https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf";
        // $img = "https://imei.eragro.co.id/files/images/logo_long.png";

        $payload = [
                "deviceid" => $deviceid,
                "phone" => $phone,
                "message" => $data->message,
                // "document" => $doc,
                // "image" => $img,
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
        
        $decoded = json_decode($result, true);

        SendWhatsappRepository::update($data->id, [
            "message_id"           => $decoded['data']['messageid'] ?? null,
            "status"               => $decoded['status'] ?? null,
            "status_text_message"  => $decoded['statustext'] ?? null,
            "status_text"          => $decoded['data']['status'] ?? null,
            "price"                => $decoded['data']['price'] ?? null,
            "data"                 => $result,
        ]);

        Log::channel('notification')->info('Send Notification To '.$phone, [
            $result,
            'setting' => [
                'url' => $url,
                'apikey' => $apikey,
                'deviceid' => $deviceid,
            ],
        ]);

        
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