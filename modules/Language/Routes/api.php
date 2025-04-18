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
    Route::get('/language_setting', [\Modules\Language\Http\Controllers\Admin\LanguageSettingController::class, 'index']);
});

Route::prefix('/')->middleware(['auth.jwt'])->group(function () {
    Route::resource('language_setting', \Modules\Language\Http\Controllers\Admin\LanguageSettingController::class)->only(['store', 'show', 'update']);
    Route::resource('language_content_form', \Modules\Language\Http\Controllers\Admin\LanguageContentFormController::class);
    Route::resource('language_data', \Modules\Language\Http\Controllers\Admin\LanguageDataController::class);
});
