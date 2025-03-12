<?php

namespace App\Permissions;

class AccessMasterData
{
    const PAYMENT_METHOD = "payment_method";
    const PRODUCT = "product";
    const PRODUCT_BOOKING_TIME = "product_booking_time";
    const PRODUCT_DETAIL = "product_detail";
    const STUDIO = "studio";
    const VOUCHER = "voucher";

    const ALL = [
        self::PAYMENT_METHOD,
        self::PRODUCT,
        self::PRODUCT_BOOKING_TIME,
        self::PRODUCT_DETAIL,
        self::STUDIO,
        self::VOUCHER,
    ];

    const TYPE_ALL = [
        self::PAYMENT_METHOD => PermissionHelper::TYPE_ALL,
        self::PRODUCT => PermissionHelper::TYPE_ALL,
        self::PRODUCT_BOOKING_TIME => PermissionHelper::TYPE_ALL,
        self::PRODUCT_DETAIL => PermissionHelper::TYPE_ALL,
        self::STUDIO => PermissionHelper::TYPE_ALL,
        self::VOUCHER => PermissionHelper::TYPE_ALL,
    ];

    const TRANSLATE = [
        self::PAYMENT_METHOD => "Metode Pembayaran",
        self::PRODUCT => "Produk",
        self::PRODUCT_BOOKING_TIME => "Waktu Booking Produk",
        self::PRODUCT_DETAIL => "Detail Produk",
        self::STUDIO => "Studio",
        self::VOUCHER => "Voucher",
    ];
}
