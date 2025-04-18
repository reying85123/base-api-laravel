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
    Route::apiResource('role', \Modules\Role\Http\Controllers\Admin\RoleController::class);
    Route::prefix('role/{roleId}')->group(function () {
        Route::get('role_auth', [\Modules\Role\Http\Controllers\Admin\RoleAuthController::class, 'show']);
        Route::put('role_auth', [\Modules\Role\Http\Controllers\Admin\RoleAuthController::class, 'update']);
        Route::get('member_role_auth', [\Modules\Role\Http\Controllers\Admin\MemberRoleAuthController::class, 'show']);
        Route::put('member_role_auth', [\Modules\Role\Http\Controllers\Admin\MemberRoleAuthController::class, 'update']);
    });
});
