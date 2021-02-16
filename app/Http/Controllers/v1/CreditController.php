<?php

namespace App\Http\Controllers\v1;

use App\Helpers\CreditHelper;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Credit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CreditController extends Controller
{
    public function index(Request $request)
    {
        $per_page = isset($request->per_page) ? $request->per_page : 50;


        $transactions = Credit::with(['transactions'])->byAccount($request->account)->byClient($request->client)
            ->orderBy('created_at', 'desc')->paginate($per_page);

        $transactions->appends(['per_page' => $per_page]);

        return response()->json(['credits' => $transactions], 200);
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
            'start_date' => 'required|date'
        ]);

        try {
            $count = Credit::count();

            $suma = $count + 1;

            $count = $count < 100 ? '0' . $suma : $suma;

            $account = Account::find($request->account_id);

            if ($account->value <= 0) {

                $account = Account::firstWhere('id', '!=', $account->id);

                if (!$account || $account->value <= 0) {
                    return response()->json(['message' => 'No tiene saldo en ninguna de sus cuentas']);
                }
            }


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
                'account_id' => $account->id,
                'status' => 'P',
                "start_date" => $request->start_date
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

    public function liquidate(Request $request)
    {
        $request->validate([
            "interest" => 'required',
            "other_value" => 'numeric',
            "transport_value" => 'numeric',
            "capital_value" => 'required|numeric',
            "fee" => 'required|numeric|max:72',
            "start_date" => 'required|date'
        ]);

        try {

            $data = [
                "interest" => $request->interest,
                "other_value" => $request->other_value,
                "transport_value" => $request->transport_value,
                "capital_value" => $request->capital_value,
                "fee" => $request->fee,
                "start_date" => $request->start_date
            ];

            return CreditHelper::liquidate($data);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
