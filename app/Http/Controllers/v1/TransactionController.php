<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 50;

        $origins = [];

        if ($request->origin) {
            $origins = explode(',', $request->origin);
        }


        $transactions = Transaction::byAccount($request->account)
            ->byOrigin($origins)
            ->byCredit($request->credit)
            ->byUser($request->user)
            ->bySupplier($request->supplier)
            ->byTypeTransaction($request->type_transaction)
            ->orderBy('created_at', 'desc')->paginate($per_page);

        $transactions->appends(['per_page' => $per_page]);

        return response()->json(['transactions' => $transactions], 200);
    }
}
