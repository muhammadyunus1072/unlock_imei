<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::group([], __DIR__ . '/web/Auth.php');
Route::group([], __DIR__ . '/web/Core.php');
Route::group([], __DIR__ . '/web/Other.php');
Route::group([], __DIR__ . '/web/Account.php');
Route::group([], __DIR__ . '/web/MasterData.php');
Route::group([], __DIR__ . '/web/Service.php');
Route::group([], __DIR__ . '/web/Transaction.php');
Route::group([], __DIR__ . '/web/Public.php');
Route::group([], __DIR__ . '/web/Report.php');

Route::middleware(['auth', 'access_permission'])->group(function () {
    Route::group(["controller" => DashboardController::class, "prefix" => "dashboard", "as" => "dashboard."], function () {
        Route::get('/', 'index')->name('index');
    });
});
