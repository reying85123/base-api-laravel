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
    Route::prefix('system')->group(function () {
        Route::get('record_log', [\Modules\System\Http\Controllers\Admin\SystemController::class, 'getRecordLog']);
        Route::get('viewcount', [\Modules\System\Http\Controllers\Admin\SystemController::class, 'getViewcount']);
    });

});