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
    "VERIFIKASI PESANAN
*Verifikasi Pesanan Anda!*  
Nomor Pesanan: *{$transaction->number}*  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama Pelanggan: *{$transaction->customer_name}*  
No. WhatsApp: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah: *".numberFormat($transaction->transactionDetails->count())." IMEI*  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Silakan klik link berikut untuk melakukan verifikasi:  
".route('transaction.edit', ['id' => simple_encrypt($transaction->id)]),

    "VERIFIKASI PESANAN
*Halo! Ini adalah konfirmasi pesanan Anda.*  
Kode: *{$transaction->number}*  
Dipesan pada: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Atas nama: *{$transaction->customer_name}*  
No. HP: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
Qty: *".numberFormat($transaction->transactionDetails->count())." IMEI*  
Total Pembayaran: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Klik link ini untuk verifikasi:  
".route('transaction.edit', ['id' => simple_encrypt($transaction->id)]),

    "VERIFIKASI PESANAN
*Pesanan Anda sedang diproses!*  
Nomor: *{$transaction->number}*  
Tanggal Pesanan: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama: *{$transaction->customer_name}*  
No WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Verifikasi lewat link ini:  
".route('transaction.edit', ['id' => simple_encrypt($transaction->id)]),

    "VERIFIKASI PESANAN
*Konfirmasi Pembelian*  
Pesanan: *{$transaction->number}*  
Tgl: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama Customer: *{$transaction->customer_name}*  
WA: *62{$transaction->customer_phone}*  
Barang: *{$transaction->transactionDetails[0]->product_name}*  
IMEI: *".numberFormat($transaction->transactionDetails->count())."* unit  
Total Biaya: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Verifikasi di:  
".route('transaction.edit', ['id' => simple_encrypt($transaction->id)]),

    "VERIFIKASI PESANAN
*Detail Pemesanan Anda*  
Nomor: *{$transaction->number}*  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama Anda: *{$transaction->customer_name}*  
No WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Link verifikasi:  
".route('transaction.edit', ['id' => simple_encrypt($transaction->id)]),

    "VERIFIKASI PESANAN
*Pesanan Telah Kami Terima!*  
Nomor: *{$transaction->number}*  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama: *{$transaction->customer_name}*  
Nomor WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
Kuantitas: *".numberFormat($transaction->transactionDetails->count())."*  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Silakan verifikasi melalui link ini:  
".route('transaction.edit', ['id' => simple_encrypt($transaction->id)]),

    "VERIFIKASI PESANAN
*Terima kasih telah memesan!*  
Kode Pesanan: *{$transaction->number}*  
Waktu: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama: *{$transaction->customer_name}*  
WhatsApp: *62{$transaction->customer_phone}*  
Item: *{$transaction->transactionDetails[0]->product_name}*  
IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Jumlah Bayar: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Verifikasi via:  
".route('transaction.edit', ['id' => simple_encrypt($transaction->id)]),

    "VERIFIKASI PESANAN
*📦 Konfirmasi Pesanan*  
Nomor Order: *{$transaction->number}*  
Tanggal Order: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama Pemesan: *{$transaction->customer_name}*  
Nomor WA: *62{$transaction->customer_phone}*  
Barang Dipesan: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Total Pembayaran: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Cek dan verifikasi di sini:  
".route('transaction.edit', ['id' => simple_encrypt($transaction->id)]),

    "VERIFIKASI PESANAN
*🎉 Terima Kasih! Pesanan Anda Sudah Masuk.*  
Nomor: *{$transaction->number}*  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama Customer: *{$transaction->customer_name}*  
WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah: *".numberFormat($transaction->transactionDetails->count())."*  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Verifikasi dengan klik:  
".route('transaction.edit', ['id' => simple_encrypt($transaction->id)]),

    "VERIFIKASI PESANAN
*Halo, ini detail pesanan Anda!*  
Nomor: *{$transaction->number}*  
Waktu Pesanan: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama Lengkap: *{$transaction->customer_name}*  
Nomor WhatsApp: *62{$transaction->customer_phone}*  
Item Pesanan: *{$transaction->transactionDetails[0]->product_name}*  
Total IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Total Harga: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Klik link berikut untuk memverifikasi:  
".route('transaction.edit', ['id' => simple_encrypt($transaction->id)]),
];

// Pilih secara acak
return $message[rand(0, count($message) - 1)];

        // return $message[rand(0, count($message) - 1)];
    }
    public static function generateAwaitingPaymentMessage($transaction)
    {
        $message = [
    "*🔔 IMEI Anda Sudah Aktif!*  
Pesanan No: *{$transaction->number}*  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama: *{$transaction->customer_name}*  
No WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah: *".numberFormat($transaction->transactionDetails->count())." IMEI*  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Pesanan Anda telah berhasil kami proses dan IMEI telah diaktifkan. Silakan lanjutkan ke proses pembayaran melalui tautan berikut:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

    "*📱 Aktivasi IMEI Telah Berhasil*  
Nomor Pesanan: *{$transaction->number}*  
Tanggal Pemesanan: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama Pemesan: *{$transaction->customer_name}*  
No. WhatsApp: *62{$transaction->customer_phone}*  
Item: *{$transaction->transactionDetails[0]->product_name}*  
Total IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Jumlah Tagihan: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Kami informasikan bahwa IMEI Anda telah aktif. Mohon segera lakukan pembayaran pada link berikut:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

    "*✅ IMEI Diaktifkan — Pembayaran Ditunggu*  
Pesanan Anda dengan nomor *{$transaction->number}* sudah diproses.  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Atas Nama: *{$transaction->customer_name}*  
Nomor WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Total Pembayaran: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Silakan lanjutkan proses pembayaran melalui link berikut:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

    "*🎉 IMEI Sudah Aktif*  
Terima kasih telah melakukan pemesanan dengan nomor *{$transaction->number}*.  
Pesanan Anda telah kami proses dan IMEI sudah aktif.  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama: *{$transaction->customer_name}*  
WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah: *".numberFormat($transaction->transactionDetails->count())." IMEI*  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Silakan selesaikan pembayaran Anda di link berikut ini:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

    "*🧾 Tagihan Pembayaran Pesanan*  
Halo *{$transaction->customer_name}*, IMEI Anda sudah kami aktifkan untuk pesanan *{$transaction->number}*.  
Tanggal Order: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
WA: *62{$transaction->customer_phone}*  
Item: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah: *".numberFormat($transaction->transactionDetails->count())." unit*  
Total Tagihan: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Silakan segera lakukan pembayaran melalui tautan berikut:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

    "*📣 IMEI Telah Diaktifkan*  
Detail Pesanan:  
- Nomor: *{$transaction->number}*  
- Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
- Nama: *{$transaction->customer_name}*  
- WA: *62{$transaction->customer_phone}*  
- Produk: *{$transaction->transactionDetails[0]->product_name}*  
- Jumlah IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
- Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Mohon segera menyelesaikan pembayaran melalui link berikut:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

    "*🚀 Aktivasi Berhasil*  
Pesanan Anda dengan nomor *{$transaction->number}* telah kami aktifkan.  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama Anda: *{$transaction->customer_name}*  
No WA: *62{$transaction->customer_phone}*  
Item: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Tagihan: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Silakan lanjutkan ke tahap pembayaran via:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

    "*📌 Notifikasi Pembayaran*  
Pesanan No: *{$transaction->number}*  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama: *{$transaction->customer_name}*  
WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Total Bayar: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

IMEI Anda telah aktif. Segera lakukan pembayaran melalui:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

    "*💡 IMEI Sudah Aktif, Yuk Bayar Sekarang!*  
Nomor Pesanan: *{$transaction->number}*  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama: *{$transaction->customer_name}*  
Nomor WA: *62{$transaction->customer_phone}*  
Item Pesanan: *{$transaction->transactionDetails[0]->product_name}*  
IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Klik link di bawah untuk melakukan pembayaran:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

    "*📝 Konfirmasi Aktivasi & Tagihan*  
Pesanan Anda *{$transaction->number}* telah selesai kami aktifkan pada *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*.  
Nama: *{$transaction->customer_name}*  
Nomor HP: *62{$transaction->customer_phone}*  
Item: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah: *".numberFormat($transaction->transactionDetails->count())."* IMEI  
Total Tagihan: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Silakan selesaikan pembayaran melalui link berikut ini:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),
];

// Pilih acak saat kirim
return $message[rand(0, count($message) - 1)];

        // return $message[rand(0, count($message) - 1)];
    }
    
    public static function generatePaymentVerificationMessage($transaction)
{
    $message = [
        "*🧾 VERIFIKASI PEMBAYARAN*  
Pesanan No: *{$transaction->number}*  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama Pelanggan: *{$transaction->customer_name}*  
Nomor WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Total Pembayaran: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Pembayaran telah dilakukan. Silakan verifikasi melalui:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

        "*📥 Konfirmasi Pembayaran Masuk*  
Nomor Pesanan: *{$transaction->number}*  
Tanggal Pemesanan: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama: *{$transaction->customer_name}*  
WA: *62{$transaction->customer_phone}*  
Item: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah: *".numberFormat($transaction->transactionDetails->count())."*  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Pelanggan telah membayar. Silakan segera verifikasi di:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

        "*💳 Pembayaran Telah Dilakukan*  
Pesanan No: *{$transaction->number}*  
Tgl: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama: *{$transaction->customer_name}*  
No WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Silakan verifikasi pembayaran via link berikut:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

        "*🔔 Notifikasi Pembayaran*  
Nomor Order: *{$transaction->number}*  
Tanggal Order: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama Pemesan: *{$transaction->customer_name}*  
No HP: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Jumlah Bayar: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Lakukan verifikasi pembayaran sekarang:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

        "*✅ Pelanggan Telah Melakukan Pembayaran*  
Detail pesanan sebagai berikut:  
- No Pesanan: *{$transaction->number}*  
- Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
- Nama: *{$transaction->customer_name}*  
- WA: *62{$transaction->customer_phone}*  
- Produk: *{$transaction->transactionDetails[0]->product_name}*  
- IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
- Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Verifikasi di link berikut:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

        "*📨 Pembayaran Diterima*  
Pesanan *{$transaction->number}* telah dibayar oleh pelanggan.  
Tanggal Pemesanan: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama: *{$transaction->customer_name}*  
WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Segera verifikasi pembayaran melalui:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

        "*🔎 Cek Pembayaran Masuk*  
Pesanan dengan no *{$transaction->number}* dari *{$transaction->customer_name}* telah dibayar.  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Tautan verifikasi:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

        "*🛠️ Verifikasi Diperlukan*  
Nomor Pesanan: *{$transaction->number}*  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama Customer: *{$transaction->customer_name}*  
Nomor WhatsApp: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
Total IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Jumlah Transfer: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Verifikasi sekarang di:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

        "*⚠️ Pembayaran Harus Dikonfirmasi*  
Detail:  
No Order: *{$transaction->number}*  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nama: *{$transaction->customer_name}*  
No WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
IMEI: *".numberFormat($transaction->transactionDetails->count())."*  
Total Bayar: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Silakan buka link berikut untuk verifikasi:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),

        "*📍 Pembayaran Baru dari Pelanggan*  
Pelanggan dengan nama *{$transaction->customer_name}* telah membayar pesanan *{$transaction->number}*.  
Tanggal: *".Carbon::parse($transaction->created_at)->translatedFormat('d F Y, H:i')."*  
Nomor WA: *62{$transaction->customer_phone}*  
Produk: *{$transaction->transactionDetails[0]->product_name}*  
Jumlah: *".numberFormat($transaction->transactionDetails->count())."* IMEI  
Total: *Rp. ".numberFormat($transaction->transactionDetails->sum('product_price'))."*  

Lakukan pengecekan dan verifikasi melalui:  
".route('public.order_payment', ['id' => simple_encrypt($transaction->id)]),
    ];

    return $message[rand(0, count($message) - 1)];
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
        $validatedData = [
            "status"               => $decoded['status'],
            "status_text_message"  => $decoded['statustext'],
        ];
        if($decoded['status'])
        {
            $validatedData = array_merge($validatedData, [
                "message_id"           => $decoded['data']['messageid'] ?? null,
                "status_text"          => $decoded['data']['status'] ?? null,
                "price"                => $decoded['data']['price'] ?? null,
                "data"                 => $result,
            ]);
        }

        SendWhatsappRepository::update($data->id, $validatedData);

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