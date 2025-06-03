<?php 

namespace App\Helpers;

class ServiceHelper
{
    public static function kirimWhatsapp($phone, $message)
    {
        
        $url = env('ADSMEDIA_REGULER_URL', null);

        $apikey = env('ADSMEDIA_API_KEY', null); // apikey , dapatkan di menu api information
        $deviceid = env('ADSMEDIA_DEVICE_ID'); //deviceid dapatkan di menu device
        $phone = "628885133453"; // 6281xxxxxxx
        $secret = "0"; // 0=data tersimpan, 1=data tersimpan dengan hash => 6288xxxxxxxx
        $tag = "app"; // optional string maksimal 50 karakter
        $message = "**Konfirmasi Pesanan Toko Serba Ada**  
        Halo [Nama],  
        Terima kasih berbelanja di Toko Serba Ada!  
        🛒 **Pesanan #** [No. Pesanan]  
        📅 **Tanggal**: 1 Juni 2025  
        📦 **Item**: [Jumlah] produk (Total: Rp [Total Harga])  
        🚚 **Pengiriman**: Estimasi 2-3 hari kerja  

        Simpan pesan ini sebagai bukti transaksi. Untuk pertanyaan, balas chat ini atau hubungi 0812-XXXX-XXXX.  

        Salam hangat,  
        Toko Serba Ada  
        www.serbaada.com";  

        $payload = [
                "deviceid" => $deviceid,
                "phone" => $phone,
                "message" => $message,
                "secret" => $secret,
                "tag" => $tag
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