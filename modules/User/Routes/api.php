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
    Route::post('user/{userId}/reset_password', [\Modules\User\Http\Controllers\Admin\UserController::class, 'resetPassword']);
    Route::apiResource('user', \Modules\User\Http\Controllers\Admin\UserController::class);
    
});