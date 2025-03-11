<?php

namespace App\Permissions;

class AccessAccount
{
    const DASHBOARD = "dashboard";
    const USER = "user";
    const PERMISSION = "permission";
    const ROLE = "role";

    const ALL = [
        self::DASHBOARD,
        self::USER,
        self::PERMISSION,
        self::ROLE,
    ];

    const TYPE_ALL = [
        self::DASHBOARD => [PermissionHelper::TYPE_READ],
        self::USER => PermissionHelper::TYPE_ALL,
        self::ROLE => PermissionHelper::TYPE_ALL,
        self::PERMISSION => PermissionHelper::TYPE_ALL,
    ];

    const TRANSLATE = [
        self::DASHBOARD => "Dashboard",
        self::USER => "Pengguna",
        self::PERMISSION => "Akses",
        self::ROLE => "Jabatan",
    ];
}
