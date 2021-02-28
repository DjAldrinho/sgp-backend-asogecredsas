<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
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

        $transactions->appends(['per_page' => $per_page]);

        return response()->json(['transactions' => $transactions], 200);
    }
}
