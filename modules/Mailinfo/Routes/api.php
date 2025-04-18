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
    Route::apiResource('mailinfo', \Modules\Mailinfo\Http\Controllers\Admin\MailinfoController::class);

    Route::prefix('/mailinfo/action')->group(function () {
        Route::post('test', [\Modules\Mailinfo\Http\Controllers\Admin\MailinfoController::class, 'test']);
    });

});