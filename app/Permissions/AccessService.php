<?php

namespace App\Permissions;

class AccessService
{
    const SCANNER = "scanner";

    const ALL = [
        self::SCANNER,
    ];

    const TYPE_ALL = [
        self::SCANNER => PermissionHelper::TYPE_ALL,
    ];

    const TRANSLATE = [
        self::SCANNER => "Scanner QR",
    ];
}
