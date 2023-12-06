<?php

use App\Http\Controllers\AuthorizationController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('login', [AuthorizationController::class, 'login']); //用户登录
    Route::group([
        'middleware' => 'auth:sanctum',
    ], function () {
        Route::post('user_info', [AuthorizationController::class, 'userInfo']); //用户信息
        Route::post('change_password', [AuthorizationController::class, 'changePassword']); //修改密码
        Route::post('logout', [AuthorizationController::class, 'logout']); //退出登录
        Route::apiResource('users', UsersController::class); //用户管理
        Route::patch('user_stint/{user_id}', [UsersController::class, 'stint']); //限制登陆
    });

});


