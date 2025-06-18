<?php

namespace App\Settings;

use App\Repositories\Core\Setting\SettingRepository;

class SettingFinance
{
    const NAME = "finance";

    const ADSMEDIA = "adsmedia";
    const WEB = "web";

    const ALL = [
        self::ADSMEDIA =>  29_000,
        self::WEB =>  10_000,
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
