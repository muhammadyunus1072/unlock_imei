<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\PublicController;


Route::group(["controller" => PublicController::class, "prefix" => "", "as" => "public."], function () {
    Route::get('/', 'index')->name('index');
    Route::get('{id}/product-order', 'product_order')->name('product-order'); 
    Route::get('contact', 'contact')->name('contact'); 
});

Route::middleware('auth')->group(function () {
    Route::group(["controller" => PublicController::class, "prefix" => "", "as" => "public."], function () {
        Route::get('{id}/booking-review', 'booking_review')->name('booking-review'); 
        Route::get('{id}/transaction', 'transaction')->name('transaction'); 
    });
});
