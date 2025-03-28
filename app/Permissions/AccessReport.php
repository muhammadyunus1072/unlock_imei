<?php

namespace App\Permissions;

class AccessReport
{
    const TRANSACTION = "transaction_report";

    const ALL = [
        self::TRANSACTION,
    ];

    const TYPE_ALL = [
        self::TRANSACTION => PermissionHelper::TYPE_ALL,
    ];

    const TRANSLATE = [
        self::TRANSACTION => "Laporan - Transaksi",
    ];
}
