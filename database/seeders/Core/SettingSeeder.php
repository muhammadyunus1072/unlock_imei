<?php

namespace Database\Seeders\Core;

use App\Models\Core\Setting\Setting;
use App\Settings\SettingSendWhatsapp;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'name' => SettingSendWhatsapp::NAME,
            'setting' => json_encode(SettingSendWhatsapp::ALL),
        ];

        Setting::create($data);
    }
}
