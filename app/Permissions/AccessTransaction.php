<?php

namespace App\Permissions;

class AccessTransaction
{
    const TRANSACTION = "transaction";

    const ALL = [
        self::TRANSACTION,
    ];

    const TYPE_ALL = [
        self::TRANSACTION => PermissionHelper::TYPE_ALL,
    ];

    const TRANSLATE = [
        self::TRANSACTION => "Transaksi",
    ];
}
