<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\ClientController;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('user', [AuthController::class, 'getAuthUser'])->middleware('auth:api');;
});

Route::middleware('auth:api')->group(function () {
    Route::group(['prefix' => 'clients'], function () {
        Route::get('all', [ClientController::class, 'index']);
        Route::get('info/{client}', [ClientController::class, 'show']);
        Route::get('search/types', [ClientController::class, 'getByType']);
        Route::patch('update/{client}', [ClientController::class, 'update']);
        Route::post('create', [ClientController::class, 'create']);
        Route::delete('delete/{client}', [ClientController::class, 'destroy']);
    });
});


