<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Admin\DelayReportController;
use Illuminate\Support\Facades\Route;

Route::prefix('administration')->group(function () {
    Route::post('assign_to_agent', [DelayReportController::class, 'assignToAgent']);
    Route::get('vendors_delay_report', [DelayReportController::class, 'getVendorDelays']);
});
