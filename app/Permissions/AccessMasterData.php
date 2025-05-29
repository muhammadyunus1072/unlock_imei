<?php

namespace App\Permissions;

class AccessMasterData
{
    const PAYMENT_METHOD = "payment_method";
    const PRODUCT = "product";
    const PRODUCT_WARRANTY = "product_warranty";
    const VOUCHER = "voucher";

    const ALL = [
        self::PAYMENT_METHOD,
        self::PRODUCT,
        self::PRODUCT_WARRANTY,
        self::VOUCHER,
    ];

    const TYPE_ALL = [
        self::PAYMENT_METHOD => PermissionHelper::TYPE_ALL,
        self::PRODUCT => PermissionHelper::TYPE_ALL,
        self::PRODUCT_WARRANTY => PermissionHelper::TYPE_ALL,
        self::VOUCHER => PermissionHelper::TYPE_ALL,
    ];

    const TRANSLATE = [
        self::PAYMENT_METHOD => "Metode Pembayaran",
        self::PRODUCT => "Produk",
        self::PRODUCT_WARRANTY => "Garansi Produk",
        self::VOUCHER => "Voucher",
    ];
}
