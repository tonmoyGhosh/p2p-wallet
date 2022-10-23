<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\LoginController;
use App\Http\Controllers\Api\v1\LogoutController;
use App\Http\Controllers\Api\v1\TransactionController;

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

Route::post('login', [LoginController::class, 'login']);

Route::group(['middleware' => ['auth:api']], function ()
{   
    Route::get('getLoginUserInfo', [TransactionController::class, 'getLoginUserInfo']);
    Route::get('getUserList', [TransactionController::class, 'getUserList']);
    Route::post('sendMoney', [TransactionController::class, 'sendMoney']);
    Route::get('statsReport', [TransactionController::class, 'statsReport']);
    Route::get('logout', [LogoutController::class, 'logout']);
    
});