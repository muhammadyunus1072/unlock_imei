<?php

namespace App\Permissions;

class AccessCore
{
    const SETTING_SEND_WHATSAPP = "setting_send_whatsapp";

    const ALL = [
        self::SETTING_SEND_WHATSAPP,
    ];

    const TYPE_ALL = [
        self::SETTING_SEND_WHATSAPP => [PermissionHelper::TYPE_READ, PermissionHelper::TYPE_UPDATE],
    ];

    const TRANSLATE = [
        self::SETTING_SEND_WHATSAPP => "Pengaturan Notifikasi",
    ];
}
