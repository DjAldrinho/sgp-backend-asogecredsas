<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Jobs\StoreTransaction;
use App\Models\Account;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function index(Request $request)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 50;

        $accounts = Account::paginate($per_page);

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
                $amount = -abs($amount);
            }

            $account_value = $account->value;
            $account->old_value = $account_value;
            $account->value = $account_value + $amount;
            $account->save();
            $account->refresh();

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
