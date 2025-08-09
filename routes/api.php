<?php

use App\Http\Controllers\CompanyUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class,'register']);
    Route::post('login',    [AuthController::class,'login']);
    Route::post('refresh',  [AuthController::class,'refresh']);
    Route::post('logout',   [AuthController::class,'logout'])->middleware('auth:api');
    Route::get('me',        [AuthController::class,'me'])->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {
    Route::post('/company/users', [CompanyUserController::class, 'store']);
});