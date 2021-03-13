<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\AccountService;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    private $service;

    /**
     * TransactionController constructor.
     */
    public function __construct(TransactionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {

        $request->validate([
            'per_page' => 'integer',
            'origin' => 'string',
            'account' => 'integer|exists:accounts,id',
            'credit' => 'integer|exists:credits,id',
            'user' => 'integer|exists:users,id',
            'client' => 'integer|exists:clients,id',
            'adviser' => 'integer|exists:advisers,id',
            'process' => 'integer|exists:processes,id',
            'type_transaction' => 'integer|exists:type_transaction,id',
            'start_date' => 'date:y-m-d',
            'end_date' => 'date:y-m-d',
        ]);

        $per_page = isset($request->per_page) ? $request->per_page : 50;

        $transactions = $this->service->getTransactions($request)->paginate($per_page);


        $count = $request->page ? ($request->page * $per_page) - $per_page : 0;

        $transactions->appends(['per_page' => $per_page]);

        return response()->json(['transactions' => $transactions, 'count' => $count], 200);
    }

    public function delete(Transaction $transaction)
    {
        try {
            if ($transaction->value) {
                if (!in_array($transaction->origin, ['credit', 'process_payment', 'commission'])) {
                    $value = $transaction->value < 0 ? abs($transaction->value) : $transaction->value;
                    $type = $transaction->origin == 'retire' ? 'add' : 'sub';
                    AccountService::updateAccount($transaction->account, $value, $type);
                    if ($transaction->origin == 'credit_payment') {
                        $credit = $transaction->credit;
                        $credit->payment += $value;
                        $credit->save();
                    }
                    $transaction->delete();
                    return response()->json(['message' => 'Transaccion eliminada correctamente!'], 200);
                }
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }
}
