<?php

use App\Http\Controllers\CompanyUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ExportController;

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

Route::middleware('auth:api')->prefix('/tasks')->group(function () {
    Route::get('/',           [TaskController::class,'index']);
    Route::post('/',          [TaskController::class,'store']);
    Route::get('/{task}',    [TaskController::class,'show']);
    Route::put('/{task}',    [TaskController::class,'update']);
    Route::delete('/{task}', [TaskController::class,'destroy']);
    Route::post('/{task}/complete', [TaskController::class,'complete']);
});

Route::middleware('auth:api')->prefix('export')->group(function () {
    Route::post('/', [ExportController::class, 'tasks']);
    Route::get('/{export}', [ExportController::class, 'show']);
    Route::get('/{export}/download', [ExportController::class, 'download'])->name('exports.download');
});