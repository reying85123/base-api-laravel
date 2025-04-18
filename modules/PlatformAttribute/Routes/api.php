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

Route::prefix('/')->group(function () {
    Route::get('/platform_attribute', [\Modules\PlatformAttribute\Http\Controllers\Admin\PlatformAttributeController::class, 'index']);
});


//登入後的API
Route::prefix('/')->middleware(['auth.jwt'])->group(function () {
    Route::put('/platform_attribute', [\Modules\PlatformAttribute\Http\Controllers\Admin\PlatformAttributeController::class, 'update']);
});