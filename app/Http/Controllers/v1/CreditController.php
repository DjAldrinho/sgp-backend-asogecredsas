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


        $transactions = Credit::with(
            [
                'transactions', 'account', 'documents', 'debtor', 'first_co_debtor', 'second_co_debtor', 'adviser',
                'refinanced', 'credit_type', 'payroll'
            ])->byAccount($request->account)->byClient($request->client)
            ->orderBy('created_at', 'desc')->paginate($per_page);

        $transactions->appends(['per_page' => $per_page]);

        return response()->json(['credits' => $transactions], 200);
    }

    public function show(Credit $credit)
    {


        $credit = Credit::with(
            [
                'transactions', 'account', 'documents', 'debtor', 'first_co_debtor', 'second_co_debtor', 'adviser',
                'refinanced', 'credit_type', 'payroll'
            ])->where('id', $credit->id)->firstOrFail();

        return response()->json(['credit' => $credit]);
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

            $account = Account::find($request->account_id);

            if ($account && $account->value > 0) {

                $count = Credit::count() + 1;

                $data = [
                    "interest" => $request->interest,
                    "other_value" => $request->other_value,
                    "transport_value" => $request->transport_value,
                    "capital_value" => $request->capital_value,
                    "fee" => $request->fee,
                    "start_date" => $request->start_date
                ];


                $total_credit = CreditHelper::liquidate($data, false);


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
                    "start_date" => $request->start_date,
                    'payment' => $total_credit
                ]);


                return response()->json(['message' => __('messages.credits.register'), 'credit' => $credit], 200);
            } else {
                return response()->json(['message' => 'No tiene saldo en la cuenta #' . $account->id . ' - ' . $account->name]);
            }

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'credit_id' => 'required|integer|exists:credits,id',
            'value' => 'required|numeric'
        ]);

        try {

            $credit = Credit::find($request->credit_id);

            if ($credit->payment > 0) {
                $credit->payment = $credit->payment - $request->value;
                if ($credit->payment <= 0) {
                    $credit->status = 'F';
                }
                $credit->save();
                $credit->refresh();

                AccountService::updateAccount($credit->account, $request->value, 'add');
                StoreTransaction::dispatchSync($credit->account->id, 'credit_payment', $request->value,
                    'Abono de credito #' . $credit->code, 3, 4, $credit->id);


                $payment = number_format(($credit->payment), 2, '.', ',');

                return response()->json(['message' => 'Valor abonado al credito #' . $credit->code . ' saldo restante: ' . $payment,
                    'credit' => $credit]);
            } else {
                $credit->status = 'F';
                $credit->save();
                $credit->refresh();
                return response()->json(['message' => 'No se puede abonar a un credito finalizado']);
            }

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
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

            return response()->json(['liquidate' => CreditHelper::liquidate($data)]);
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
            $account = Account::find($credit->account_id);

            if ($account && $account->value > 0) {
                if ($credit->status == 'P') {
                    $total = $credit->capital_value + $credit->transport_value + $credit->other_value;


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
            } else {
                return response()->json(['message' => 'No tiene saldo en la cuenta #' . $account->id . ' - ' . $account->name]);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 'line' => $exception->getLine()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function refinance(Request $request)
    {
        $request->validate([
            'credit_id' => 'required|integer|exists:credits,id',
            'capital_value' => 'required|numeric',
            'transport_value' => 'numeric',
            'fee' => 'integer'
        ]);

        try {

            $credit = Credit::find($request->credit_id);


            if ($credit->status == 'A') {

                $account = Account::find($credit->account_id);

                if ($account && $account->value > 0) {

                    $count = Credit::count() + 1;

                    $fee = $request->fee ? $request->fee : $credit->fee;

                    $date = date('Y-m-d');

                    $data = [
                        "interest" => $credit->interest,
                        "other_value" => $credit->payment,
                        "transport_value" => $request->transport_value,
                        "capital_value" => $request->capital_value,
                        "fee" => $fee,
                        "start_date" => $date
                    ];

                    $total_credit = CreditHelper::liquidate($data, false);

                    $new_credit = Credit::create([
                        'code' => 'C' . time() . '-' . $count,
                        'payroll_id' => $credit->payroll_id,
                        'credit_type_id' => $credit->credit_type_id,
                        'debtor_id' => $credit->debtor_id,
                        'first_co_debtor' => $credit->first_co_debtor,
                        'second_co_debtor' => $credit->second_co_debtor,
                        'other_value' => $credit->payment,
                        'capital_value' => $request->capital_value,
                        'transport_value' => $request->transport_value,
                        'interest' => $credit->interest,
                        'fee' => $fee,
                        'account_id' => $credit->account_id,
                        'status' => 'A',
                        "start_date" => $date,
                        'payment' => $total_credit
                    ]);

                    $credit->status = 'F';
                    $credit->refinanced = true;
                    $credit->refinanced_id = $new_credit->id;
                    $credit->save();

                    AccountService::updateAccount($account, $new_credit->capital_value, 'sub');

                    StoreTransaction::dispatchSync($account->id, 'credit', -abs($new_credit->capital_value),
                        'Desembolso de Credito refinanciado', 3, $credit->credit_type_id, $new_credit->id);

                    return response()->json(['message' => 'Credito renovado correctamente',
                        'credit' => $credit, 'new_credit' => $new_credit], Response::HTTP_OK);
                } else {
                    return response()->json(['message' => 'No tiene saldo en la cuenta #' . $account->id . ' - ' . $account->name]);
                }
            } else {
                return response()->json(['message' => 'El Credito se encuentra finalizado y no se puede refinanciar,
                sugerimos generar un nuevo credito'], Response::HTTP_OK);
            }
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage(), 'line' => $exception->getLine()], Response::HTTP_BAD_REQUEST);
        }
    }

    public function cancel(Credit $credit)
    {
        if ($credit->status == 'P') {
            $credit->status = 'C';
            $credit->save();
            $credit->refresh();

            return response()->json(['message' => 'Se ha finalizado correctamente el credito'], Response::HTTP_OK);
        } else {
            return response()->json(['message' => 'Solo se puede cancelar creditos pendientes'], Response::HTTP_BAD_REQUEST);
        }
    }
}
