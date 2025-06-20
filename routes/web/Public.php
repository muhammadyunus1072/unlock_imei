<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\PublicController;


Route::group(["controller" => PublicController::class, "prefix" => "", "as" => "public."], function () {
    Route::get('/', 'index')->name('index');
    Route::get('{id}/product-order', 'product_order')->name('product-order'); 
    Route::get('{id}/order-invoice', 'order_invoice')->name('order_invoice'); 
    Route::get('{id}/order-payment', 'order_payment')->name('order_payment'); 
    Route::get('order-check', 'order_check')->name('order-check'); 
    Route::get('contact', 'contact')->name('contact'); 
    Route::get('coba', 'coba')->name('coba'); 
});

Route::middleware('auth')->group(function () {
    Route::group(["controller" => PublicController::class, "prefix" => "", "as" => "public."], function () {
        Route::get('{id}/booking-review', 'booking_review')->name('booking-review'); 
        Route::get('{id}/transaction', 'transaction')->name('transaction'); 
    });
});
