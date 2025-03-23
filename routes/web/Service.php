<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\PublicController;
use App\Http\Controllers\Service\ScannerController;
use App\Http\Controllers\Service\ServiceController;

Route::middleware(['auth', 'access_permission'])->group(function () {
    Route::group(["controller" => ServiceController::class, "prefix" => "service", "as" => "service."], function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::get('{id}/edit', 'edit')->name('edit');
    });
    Route::group(["controller" => ScannerController::class, "prefix" => "scanner", "as" => "scanner."], function () {
        Route::get('/', 'index')->name('index');
    });
});

Route::middleware('auth')->group(function () {
    Route::group(["controller" => PublicController::class, "prefix" => "service", "as" => "service."], function () {
        Route::get('{id}/generate', 'generate')->name('generate'); 
    });
});
