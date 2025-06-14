<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function send_whatsapp()
    {
        return view('app.core.setting.send-whatsapp.index');
    }
}
