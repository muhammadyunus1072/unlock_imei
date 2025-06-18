<?php

namespace App\Permissions;

class AccessCore
{
    const SETTING_SEND_WHATSAPP = "setting_send_whatsapp";
    const SETTING_FINANCE = "setting_finance";

    const ALL = [
        self::SETTING_SEND_WHATSAPP,
        self::SETTING_FINANCE,
    ];

    const TYPE_ALL = [
        self::SETTING_SEND_WHATSAPP => [PermissionHelper::TYPE_READ, PermissionHelper::TYPE_UPDATE],
        self::SETTING_FINANCE => [PermissionHelper::TYPE_READ, PermissionHelper::TYPE_UPDATE],
    ];

    const TRANSLATE = [
        self::SETTING_SEND_WHATSAPP => "Pengaturan Notifikasi",
        self::SETTING_FINANCE => "Pengaturan Keuangan",
    ];
}
