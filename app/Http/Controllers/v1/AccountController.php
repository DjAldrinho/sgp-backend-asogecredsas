<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Jobs\StoreTransaction;
use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 50;

        $accounts = Account::with(['transactions'])->paginate($per_page);

        $accounts->appends(['per_page' => $per_page]);

        return response()->json(['accounts' => $accounts], 200);
    }

    public function show(Account $account)
    {
        return response()->json(['account' => $account], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:accounts',
            'account_number' => 'required|string|unique:accounts',
            'value' => 'required|integer'
        ]);

        try {
            $account = Account::create([
                'name' => $request->name,
                'account_number' => $request->account_number,
                'value' => $request->value,
                'old_value' => $request->value
            ]);

            StoreTransaction::dispatchSync($account->id, 'deposit', $account->value, 'Creacion de cuenta');

            return response()->json(['message' => __('messages.accounts.register'), 'account' => $account], 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'name' => 'required|string|unique:accounts,name,' . $account->id
        ]);

        $account->name = $request->name;
        $account->save();

        return response()->json(['message' => __('messages.accounts.updated'), 'account' => $account], 200);
    }

    public function changeAccount(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:deposit,retire',
            'account_id' => 'required|integer|exists:accounts,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'type_transaction' => 'required|integer|exists:type_transaction,id',
            'amount' => 'required|integer',
            'commentary' => 'string'
        ]);

        try {
            $amount = (int)$request->amount;

            $account = Account::firstWhere(['id' => $request->account_id]);

            if ($request->type === 'retire') {
                if ($amount > $account->value) {
                    return response()->json(['message' => 'No tiene saldo en la cuenta #' . $account->id . ' - ' . $account->name], Response::HTTP_BAD_REQUEST);
                }

                $amount = -abs($amount);

                AccountService::updateAccount($account, $amount, 'retire');
            } else {
                AccountService::updateAccount($account, $amount, 'add');
            }


            StoreTransaction::dispatchSync($account->id, $request->type, $amount,
                $request->commentary, $request->supplier_id, $request->type_transaction);

            return response()->json(['message' => ucfirst($request->type) . ' generado!', 'account' => $account], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function destroy(Account $account)
    {
        try {
            $account->delete();
            return response()->json(['message' => __('messages.accounts.deleted')], 200);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

}
