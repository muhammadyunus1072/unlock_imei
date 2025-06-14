<?php

use App\Http\Controllers\Core\SettingController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'access_permission'])->group(function () {

    Route::group(["controller" => SettingController::class], function () {
        // Core
        Route::get('/setting_send_whatsapp', 'send_whatsapp')->name('setting_send_whatsapp.index');
    });
});
