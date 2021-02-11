<?php


use App\Http\Controllers\v1\AccountController;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\ClientController;
use App\Http\Controllers\v1\CreditController;
use App\Http\Controllers\v1\LawyerController;
use App\Http\Controllers\v1\SupplierController;
use App\Http\Controllers\v1\TransactionController;
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
    Route::post('register', [AuthController::class, 'register'])->middleware('auth:api');
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('user', [AuthController::class, 'getAuthUser'])->middleware('auth:api');
    Route::patch('change-password', [AuthController::class, 'changePassword'])->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {

    Route::group(['prefix' => 'clients',  'middleware' => 'validate_admin'], function () {
        Route::get('all', [ClientController::class, 'index']);
        Route::get('info/{client}', [ClientController::class, 'show']);
        Route::get('search/types', [ClientController::class, 'getByType']);
        Route::get('template', [ClientController::class, 'getTemplate']);
        Route::patch('update/{client}', [ClientController::class, 'update']);
        Route::post('create', [ClientController::class, 'create']);
        Route::post('create-massive', [ClientController::class, 'createMassive']);
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

    Route::group(['prefix' => 'accounts', 'middleware' => 'validate_admin'], function () {
        Route::get('all', [AccountController::class, 'index']);
        Route::post('create', [AccountController::class, 'create']);
        Route::patch('change-account', [AccountController::class, 'changeAccount']);
    });

    Route::group(['prefix' => 'transactions'], function () {
        Route::get('all', [TransactionController::class, 'index']);
    });

    Route::group(['prefix' => 'credits'], function () {
        Route::get('all', [CreditController::class, 'index']);
    });

    Route::group(['prefix' => 'type-transaction'], function () {
        Route::get('all', [TypeTransactionController::class, 'index']);
        Route::patch('update/{type}', [TypeTransactionController::class, 'update']);
        Route::post('create', [TypeTransactionController::class, 'create']);
        Route::delete('delete/{type}', [TypeTransactionController::class, 'destroy']);
    });
});


