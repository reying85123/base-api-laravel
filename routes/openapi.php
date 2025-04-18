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
 * 開放功能API
 * --------------------------------------------------------------------------
 * 
 * 基於開放功能API Route
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
$moduleRoutes = glob(base_path('modules/*/Routes/openapi.php'));
foreach ($moduleRoutes as $routeFile) {
    require_once $routeFile;
}

/**
 * --------------------------------------------------------------------------
 * Base API
 * --------------------------------------------------------------------------
 * 
 * 開放基本功能API
 */


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
