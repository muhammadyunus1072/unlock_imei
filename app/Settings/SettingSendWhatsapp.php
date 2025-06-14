<?php

namespace App\Settings;

use App\Repositories\Core\Setting\SettingRepository;

class SettingSendWhatsapp
{
    const NAME = "send_whatsapp";

    const ADSMEDIA_URL = "adsmedia_url";
    const ADSMEDIA_API_KEY = "adsmedia_api_key";
    const ADSMEDIA_DEVICE_ID = "adsmedia_device_id";
    const ADMIN_PHONE = "admin_phone";

    const ALL = [
        self::ADSMEDIA_URL =>  "https://app.adsmedia.id/api/wareguler/send-message",
        self::ADSMEDIA_API_KEY =>  "M4uFpuxmrUdqM8sHlAzY97JFPE7vx4GPBE02s8s5B29fEpGTpJI4VOEnfSHY",
        self::ADSMEDIA_DEVICE_ID =>  "8LUEIQNQE",
        self::ADMIN_PHONE =>  "82235258870",
    ];

    public $parsedSetting;

    public function __construct()
    {
        $setting = SettingRepository::findBy(whereClause: [['name', self::NAME]]);
        $this->parsedSetting = json_decode($setting->setting, true);
    }

    public static function get($key)
    {
        $setting = app(self::class);

        if (!isset($setting->parsedSetting[$key])) {
            return null;
        }

        return $setting->parsedSetting[$key];
    }
}
