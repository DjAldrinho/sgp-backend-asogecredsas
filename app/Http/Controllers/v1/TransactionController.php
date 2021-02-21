<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {

        $request->validate([
            'per_page' => 'integer',
            'origin' => 'string',
            'account' => 'integer|exists:accounts,id',
            'credit' => 'integer|exists:credits,id',
            'user' => 'integer|exists:users,id',
            'type_transaction' => 'integer|exists:type_transaction,id',
            'start_date' => 'date',
            'end_date' => 'date',
        ]);

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
            ->byDate($request->start_date, $request->end_date)
            ->orderBy('created_at', 'desc')->paginate($per_page);

        $transactions->appends(['per_page' => $per_page]);

        return response()->json(['transactions' => $transactions], 200);
    }
}
