<?php

use App\Http\Controllers\Other\CaptchaController;
use App\Http\Controllers\Other\MenuNotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/reload-captcha', [CaptchaController::class, 'reload'])->name('reload_captcha');

Route::middleware('auth')->group(function () {
    Route::get("/menu-notification", [MenuNotificationController::class, "index"])->name('menu_notification');
});