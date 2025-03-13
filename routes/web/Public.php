<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\PublicController;


Route::group(["controller" => PublicController::class, "prefix" => "", "as" => "public."], function () {
    Route::get('/', 'index')->name('index');
    Route::get('{id}/product-booking', 'product_booking')->name('product-booking');
});
