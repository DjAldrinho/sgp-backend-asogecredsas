<?php

namespace App\Http\Controllers\v1;

use App\Helpers\CreditHelper;
use App\Helpers\FileManager;
use App\Http\Controllers\Controller;
use App\Jobs\StoreTransaction;
use App\Models\Account;
use App\Models\Credit;
use App\Models\CreditDocument;
use App\Services\AccountService;
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
            'commission' => 'required_with:adviser_id|numeric',
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

    //Abonos WIP
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

    public function approve(Request $request)
    {

        $request->validate([
            'credit_id' => 'required|integer|exists:credits,id',
            'files' => 'required',
            'files.*' => 'mimes:doc,pdf,docx,zip,jpeg,jpg,png',
            'commentary' => 'string'
        ]);

        try {

            $documents = [];

            $credit = Credit::find($request->credit_id);

            if ($credit->status == 'P') {
                $total = $credit->capital_value + $credit->transport_value + $credit->other_value;


                $account = Account::find($credit->account_id);
                $credit->status = 'A';

                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $key => $file) {
                        $documents[$key] = new CreditDocument(['document_file' => FileManager::uploadPublicFiles($file, 'documents_credits')]);
                    }
                }


                if ($credit->commission) {
                    $total_commission = ($total * ($credit->commission / 100));
                    AccountService::updateAccount($account, $total_commission, 'sub');
                    StoreTransaction::dispatchSync($account->id, 'commission', -abs($total_commission),
                        'Comision de ' . $credit->commission . '%', 2, 4, $credit->id);
                }

                $credit->commentary = $request->commentary;

                $credit->save();
                $credit->documents()->saveMany($documents);
                $credit->refresh();

                AccountService::updateAccount($account, $credit->capital_value, 'sub');

                StoreTransaction::dispatchSync($account->id, 'credit', -abs($credit->capital_value),
                    'Desembolso de Credito', 3, $credit->credit_type_id, $credit->id);


                return response()->json(['message' => 'Credito aprobado correctamente', 'credit' => $credit], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Su credito ya se encuentra aprobado'], Response::HTTP_MULTI_STATUS);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 'line' => $exception->getLine()], Response::HTTP_BAD_REQUEST);
        }
    }
}
