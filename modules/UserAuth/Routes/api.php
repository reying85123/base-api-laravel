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
    Route::prefix('auth')->group(function () {
        Route::post('login', [\Modules\UserAuth\Http\Controllers\Admin\UserAuthController::class, 'login']);
        Route::post('refresh_token', [\Modules\UserAuth\Http\Controllers\Admin\UserAuthController::class, 'refreshToken']);
    });
});


//登入後的API
Route::prefix('/')->middleware(['auth.jwt'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::get('me', [\Modules\UserAuth\Http\Controllers\Admin\UserAuthController::class, 'getProfile']);
        Route::patch('me', [\Modules\UserAuth\Http\Controllers\Admin\UserAuthController::class, 'updateProfile']);
        Route::post('change_password', [\Modules\UserAuth\Http\Controllers\Admin\UserAuthController::class, 'changeMyPassword']);
        Route::get('permission', [\Modules\UserAuth\Http\Controllers\Admin\UserAuthController::class, 'permission']);
    });
});