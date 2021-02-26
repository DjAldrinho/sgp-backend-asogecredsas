<?php


use App\Http\Controllers\v1\AccountController;
use App\Http\Controllers\v1\AdviserController;
use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\ClientController;
use App\Http\Controllers\v1\CreditController;
use App\Http\Controllers\v1\CreditTypeController;
use App\Http\Controllers\v1\DashboardController;
use App\Http\Controllers\v1\LawyerController;
use App\Http\Controllers\v1\PayrollController;
use App\Http\Controllers\v1\ProcessController;
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
    Route::get('users', [AuthController::class, 'index']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register'])->middleware('auth:api');
    Route::get('logout', [AuthController::class, 'logout'])->middleware('auth:api');
    Route::get('user', [AuthController::class, 'getAuthUser'])->middleware('auth:api');
    Route::patch('change-password', [AuthController::class, 'changePassword'])->middleware('auth:api');
});

Route::middleware('auth:api')->group(function () {

    Route::group(['prefix' => 'clients', 'middleware' => 'validate_admin'], function () {
        Route::get('all', [ClientController::class, 'index']);
        Route::get('info/{client}', [ClientController::class, 'show']);
        Route::get('template', [ClientController::class, 'getTemplate']);
        Route::patch('update/{client}', [ClientController::class, 'update']);
        Route::post('create', [ClientController::class, 'create']);
        Route::post('create-massive', [ClientController::class, 'createMassive']);
        Route::delete('delete/{client}', [ClientController::class, 'destroy']);
    });

    Route::group(['prefix' => 'lawyers', 'middleware' => 'validate_admin'], function () {
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

    Route::group(['prefix' => 'advisers'], function () {
        Route::get('all', [AdviserController::class, 'index']);
        Route::get('info/{adviser}', [AdviserController::class, 'show']);
        Route::patch('update/{adviser}', [AdviserController::class, 'update']);
        Route::post('create', [AdviserController::class, 'create']);
        Route::delete('delete/{adviser}', [AdviserController::class, 'destroy']);
    });

    Route::group(['prefix' => 'accounts', 'middleware' => 'validate_admin'], function () {
        Route::get('all', [AccountController::class, 'index']);
        Route::get('get/{account}', [AccountController::class, 'show']);
        Route::post('create', [AccountController::class, 'create']);
        Route::patch('update/{account}', [AccountController::class, 'update']);
        Route::patch('change-account', [AccountController::class, 'changeAccount']);
        Route::delete('delete/{account}', [AccountController::class, 'destroy']);
    });

    Route::group(['prefix' => 'transactions'], function () {
        Route::get('all', [TransactionController::class, 'index']);
    });

    Route::group(['prefix' => 'dashboard'], function () {
        Route::get('all', [DashboardController::class, 'index']);
    });

    Route::group(['prefix' => 'credits'], function () {
        Route::get('all', [CreditController::class, 'index']);
        Route::get('expired', [CreditController::class, 'getCreditsExpired']);
        Route::get('info/{credit}', [CreditController::class, 'show']);
        Route::patch('cancel/{credit}', [CreditController::class, 'cancel']);
        Route::post('liquidate', [CreditController::class, 'liquidate']);
        Route::post('approve', [CreditController::class, 'approve']);
        Route::post('create', [CreditController::class, 'create']);
        Route::post('deposit', [CreditController::class, 'deposit']);
        Route::post('refinance', [CreditController::class, 'refinance']);
        Route::post('add-commentary', [CreditController::class, 'addCommentary']);
        Route::delete('delete-document/{document}', [CreditController::class, 'removeDocument']);
    });

    Route::group(['prefix' => 'credit-types'], function () {
        Route::get('all', [CreditTypeController::class, 'index']);
        Route::post('create', [CreditTypeController::class, 'create']);
        Route::patch('update/{creditType}', [CreditTypeController::class, 'update']);
        Route::delete('delete/{creditType}', [CreditTypeController::class, 'destroy']);
    });

    Route::group(['prefix' => 'processes'], function () {
        Route::get('all', [ProcessController::class, 'index']);
        Route::get('info/{process}', [ProcessController::class, 'show']);
        Route::post('create', [ProcessController::class, 'create']);
        Route::post('deposit', [ProcessController::class, 'deposit']);
    });

    Route::group(['prefix' => 'payrolls'], function () {
        Route::get('all', [PayrollController::class, 'index']);
        Route::post('create', [PayrollController::class, 'create']);
        Route::patch('update/{payroll}', [PayrollController::class, 'update']);
        Route::delete('delete/{payroll}', [PayrollController::class, 'destroy']);
    });

    Route::group(['prefix' => 'type-transaction'], function () {
        Route::get('all', [TypeTransactionController::class, 'index']);
        Route::patch('update/{typeTransaction}', [TypeTransactionController::class, 'update']);
        Route::post('create', [TypeTransactionController::class, 'create']);
        Route::delete('delete/{typeTransaction}', [TypeTransactionController::class, 'destroy']);
    });
});


