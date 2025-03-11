<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Transaction\OrderController;
use App\Http\Controllers\Transaction\TransactionController;

Route::middleware(['auth', 'access_permission'])->group(function () {
    Route::group(["controller" => TransactionController::class, "prefix" => "transaction", "as" => "transaction."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');
    });
});
