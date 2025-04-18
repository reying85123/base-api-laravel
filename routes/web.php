<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/**
 * --------------------------------------------------------------------------
 * File Route
 * --------------------------------------------------------------------------
 */

Route::prefix('file')->middleware(['accessContentDisposition'])->group(function(){
    Route::get('{show_type}/{file_uuid}/{filename?}', [\App\Http\Controllers\Base\FileStorageController::class, 'getFile'])->name('files');
    
    Route::get('info/{file_uuid}', [\App\Http\Controllers\Base\FileStorageController::class, 'fileInfo'])
        ->middleware([\App\Http\Middleware\ForceJsonResponse::class])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);;

    Route::post('upload', [\App\Http\Controllers\Base\FileStorageController::class, 'upload'])
        ->middleware([\App\Http\Middleware\ForceJsonResponse::class])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
});