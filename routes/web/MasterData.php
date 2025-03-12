<?php

use App\Http\Controllers\MasterData\PaymentMethodController;
use App\Http\Controllers\MasterData\ProductBookingTimeController;
use App\Http\Controllers\MasterData\ProductController;
use App\Http\Controllers\MasterData\ProductDetailController;
use App\Http\Controllers\MasterData\StudioController;
use App\Http\Controllers\MasterData\VoucherController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'access_permission'])->group(function () {
    Route::group(["controller" => StudioController::class, "prefix" => "studio", "as" => "studio."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');
    });
    Route::group(["controller" => ProductController::class, "prefix" => "product", "as" => "product."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');
    });
    Route::group(["controller" => ProductBookingTimeController::class, "prefix" => "product_booking_time", "as" => "product_booking_time."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');
    });
    Route::group(["controller" => ProductDetailController::class, "prefix" => "product_detail", "as" => "product_detail."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');
    });
    Route::group(["controller" => VoucherController::class, "prefix" => "voucher", "as" => "voucher."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');
    });
    Route::group(["controller" => PaymentMethodController::class, "prefix" => "payment_method", "as" => "payment_method."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');
    });
});
