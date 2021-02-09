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

            StoreTransaction::dispatchSync($account->id, 'Deposito', $account->value);

            return response()->json(['message' => 'Successfully account created!', 'account' => $account], 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function deposit(Request $request)
    {

    }

    public function retire(Request $request)
    {

    }
}
