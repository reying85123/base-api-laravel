<?php

use Illuminate\Http\Request;
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

use Jiannei\Response\Laravel\Support\Facades\Response;

/**
 * --------------------------------------------------------------------------
 * 前台功能API
 * --------------------------------------------------------------------------
 * 
 * 基於前台功能的API Route
 */

/*
|--------------------------------------------------------------------------
| Module API Routes
|--------------------------------------------------------------------------
|
| 這個區塊用來引入功能模組Route
|
|
 */
$moduleRoutes = glob(base_path('modules/*/Routes/clientapi.php'));
foreach ($moduleRoutes as $routeFile) {
    require_once $routeFile;
}

/**
 * --------------------------------------------------------------------------
 * Base API
 * --------------------------------------------------------------------------
 * 
 * 前台基本功能API
 */

Route::prefix('/')->group(function () {
    //通用
    Route::prefix('/')->group(function () {
        Route::resource('country_code', \App\Http\Controllers\Base\CountryCodeController::class)->only([
            'index'
        ]);
    });
});

/**
 * --------------------------------------------------------------------------
 * Project API
 * --------------------------------------------------------------------------
 * 
 * 專案API放在以下
 */



/**
 * API Fallback
 */
Route::fallback(function () {
    abort(404, 'API resource not found');
});
