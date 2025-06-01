<?php 

namespace App\Helpers;

class ServiceHelper
{
    public static function kirimWhatsapp()
    {
        $url = env('ADSMEDIA_URL');

        $apikey = env('ADSMEDIA_API_KEY');
        $phone = "6285183346446";
        $template = "waofficial_otp_01_en";
        $secret = "0";
        $tag = "app";

        /*
        // content: {{1}} is your verification code.
        */

        $data = [
            "parameter1"=>"Pesan Konfirmasi Pembelian Tiket Terima kasih telah membeli tiket untuk konser kami. Berikut adalah detail pemesanan dan informasi anda: {{2}} Detail Acara Konser: {{3}} Detail Pembelian Tiket: {{4}} Klik link di bawah ini untuk mengunduh tiket Anda: {{5}} Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi kami. Sampai jumpa di konser! Kontak Admin: {{6}}",
        ];


        $payload = [
        "messaging_product" => "waofficialotp",
        "phone" => $phone,
        "template" => $template,
        "secret" => $secret,
        "tag" => $tag,
        "parameters" => $data];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPHEADER,
        array(
        "Authorization: Bearer $apikey",
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

        // dd([
        //     'response' => $response,
        //     'errors' => $errors,
        //     'code' => $code,
        // ]);
        logger($result);

        return [
            'code' => $result['status'],
            'response' => $result,
            'errors' => $result['status'] ? null : $result['statusText'],
        ];
    }
}