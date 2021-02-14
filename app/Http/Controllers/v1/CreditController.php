<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Credit;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    public function index(Request $request)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 50;


        $transactions = Credit::byAccount($request->account)->byClient($request->client)
            ->orderBy('created_at', 'desc')->paginate($per_page);

        $transactions->appends(['per_page' => $per_page]);

        return response()->json(['transactions' => $transactions], 200);
    }

    public function create(Request $request)
    {

        $request->validate([
            'payroll_id' => 'required|integer|exists:payrolls,id',
            'credit_type_id' => 'required|integer|exists:credit_types,id',
            'debtor_id' => 'required|integer|exists:clients,id',
            'first_co_debtor' => 'integer|exists:clients,id',
            'second_co_debtor' => 'integer|exists:clients,id',
            'capital_value' => 'required|numeric',
            'transport_value' => 'numeric',
            'other_value' => 'numeric',
            'interest' => 'required|numeric',
            'commission' => 'numeric',
            'fee' => 'required|integer',
            'adviser_id' => 'integer|exists:advisers,id',
            'account_id' => 'required|integer|exists:accounts,id',
        ]);

        try {
            $count = Credit::count();

            $count = $count + 1;

            $credit = Credit::create([
                'code' => 'C' . time() . '-' . $count,
                'payroll_id' => $request->payroll_id,
                'credit_type_id' => $request->credit_type_id,
                'debtor_id' => $request->debtor_id,
                'first_co_debtor' => $request->first_co_debtor,
                'second_co_debtor' => $request->second_co_debtor,
                'capital_value' => $request->capital_value,
                'transport_value' => $request->transport_value,
                'other_value' => $request->other_value,
                'interest' => $request->interest,
                'commission' => $request->commission,
                'fee' => $request->fee,
                'adviser_id' => $request->adviser_id,
                'account_id' => $request->account_id,
            ]);

            return response()->json(['message' => __('messages.credits.register'), 'credit' => $credit], 200);

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'credit_id' => 'required|integer|exists:credits,id'
        ]);
    }
}
