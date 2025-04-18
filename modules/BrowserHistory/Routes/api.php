<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('/')->middleware(['auth.jwt'])->group(function () {
    Route::prefix('browser_history')->group(function () {
        Route::get('/', [\Modules\BrowserHistory\Http\Controllers\Admin\BrowserHistoryController::class, 'index']);
        Route::prefix('action')->group(function () {
            Route::get('traffic_overview', [\Modules\BrowserHistory\Http\Controllers\Admin\BrowserHistoryController::class, 'trafficChartReport']);
            Route::get('device_type_chart_report', [\Modules\BrowserHistory\Http\Controllers\Admin\BrowserHistoryController::class, 'deviceTypeChartReport']);
            Route::get('browser_chart_report', [\Modules\BrowserHistory\Http\Controllers\Admin\BrowserHistoryController::class, 'browserChartReport']);
            Route::get('traffic_chart_report', [\Modules\BrowserHistory\Http\Controllers\Admin\BrowserHistoryController::class, 'trafficChartReport']);
        });
    });
});