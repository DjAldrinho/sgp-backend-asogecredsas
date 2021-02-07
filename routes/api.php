<?php


use App\Http\Controllers\v1\AccountController;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\ClientController;
use App\Http\Controllers\v1\LawyerController;
use App\Http\Controllers\v1\SupplierController;
use App\Http\Controllers\v1\TypeTransactionController;
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
    Route::get('user', [AuthController::class, 'getAuthUser'])->middleware('auth:api');
    Route::patch('change-password', [AuthController::class, 'changePassword'])->middleware('auth:api');
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

    Route::group(['prefix' => 'lawyers'], function () {
        Route::get('all', [LawyerController::class, 'index']);
        Route::get('info/{lawyer}', [LawyerController::class, 'show']);
        Route::patch('update/{lawyer}', [LawyerController::class, 'update']);
        Route::post('create', [LawyerController::class, 'create']);
        Route::delete('delete/{lawyer}', [LawyerController::class, 'destroy']);
    });

    Route::group(['prefix' => 'suppliers'], function () {
        Route::get('all', [SupplierController::class, 'index']);
        Route::patch('update/{supplier}', [SupplierController::class, 'update']);
        Route::post('create', [SupplierController::class, 'create']);
        Route::delete('delete/{supplier}', [SupplierController::class, 'destroy']);
    });

    Route::group(['prefix' => 'accounts'], function () {
        Route::post('deposit', [AccountController::class, 'deposit']);
        Route::post('retire', [AccountController::class, 'retire']);
    });

    Route::group(['prefix' => 'type-transaction'], function () {
        Route::get('all', [TypeTransactionController::class, 'index']);
        Route::patch('update/{type}', [TypeTransactionController::class, 'update']);
        Route::post('create', [TypeTransactionController::class, 'create']);
        Route::delete('delete/{type}', [TypeTransactionController::class, 'destroy']);
    });
});


