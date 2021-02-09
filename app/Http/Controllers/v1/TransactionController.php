<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request, Account $account)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 50;


        $transactions = Transaction::byAccount($account->id)->origin($request->origin)
            ->paginate($per_page);

        $transactions->appends(['per_page' => $per_page]);

        return response()->json(['transactions' => $transactions], 200);
    }
}
