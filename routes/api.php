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
 * 後台管理功能API
 * --------------------------------------------------------------------------
 * 
 * 基於後台管理功能的API Route
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
$moduleRoutes = glob(base_path('modules/*/Routes/api.php'));
foreach ($moduleRoutes as $routeFile) {
    require_once $routeFile;
}

/**
 * --------------------------------------------------------------------------
 * Project API
 * --------------------------------------------------------------------------
 * 
 * 專案API
 */



/**
 * API Fallback
 */
Route::fallback(function () {
    abort(404, 'API resource not found');
});
