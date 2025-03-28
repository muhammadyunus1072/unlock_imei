<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Report\TransactionReportController;

Route::middleware(['auth', 'access_permission'])->group(function () {
    Route::group(["controller" => TransactionReportController::class, "prefix" => "transaction_report", "as" => "transaction_report."], function () {
        Route::get('/', 'index')->name('index');
    });
});
