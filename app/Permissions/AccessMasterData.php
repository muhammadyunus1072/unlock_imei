<?php

namespace App\Permissions;

class AccessMasterData
{
    const PAYMENT_METHOD = "payment_method";
    const PRODUCT = "product";
    const STUDIO = "studio";
    const VOUCHER = "voucher";

    const ALL = [
        self::PAYMENT_METHOD,
        self::PRODUCT,
        self::STUDIO,
        self::VOUCHER,
    ];

    const TYPE_ALL = [
        self::PAYMENT_METHOD => PermissionHelper::TYPE_ALL,
        self::PRODUCT => PermissionHelper::TYPE_ALL,
        self::STUDIO => PermissionHelper::TYPE_ALL,
        self::VOUCHER => PermissionHelper::TYPE_ALL,
    ];

    const TRANSLATE = [
        self::PAYMENT_METHOD => "Metode Pembayaran",
        self::PRODUCT => "Produk",
        self::STUDIO => "Studio",
        self::VOUCHER => "Voucher",
    ];
}
