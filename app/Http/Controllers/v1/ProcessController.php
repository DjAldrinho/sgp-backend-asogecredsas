<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Jobs\StoreTransaction;
use App\Models\Credit;
use App\Models\Process;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProcessController extends Controller
{
    public function index(Request $request)
    {

        $request->validate([
            'lawyer' => 'integer|exists:lawyers,id',
            'credit' => 'integer|exists:credits,id',
            'start_date' => 'date:y-m-d',
            'end_date' => 'date:y-m-d',
            'status' => 'string'
        ]);

        $per_page = isset($request->per_page) ? $request->per_page : 50;

        $status = null;

        if ($request->status) {
            $status = explode(',', $request->status);
        }

        $processes = Process::with([
            'lawyer', 'credit', 'credit.debtor', 'credit.first_co_debtor', 'credit.second_co_debtor'
        ])->byCredit($request->credit)
            ->byLawyer($request->lawyer)
            ->byDate($request->start_date, $request->end_date)
            ->byStatus($status)
            ->paginate($per_page);

        $processes->appends(['per_page' => $per_page]);

        return response()->json(['processes' => $processes], 200);
    }

    public function create(Request $request)
    {
        $request->validate([
            'lawyer_id' => 'integer|required|exists:lawyers,id',
            'credit_id' => 'integer|required|exists:credits,id',
            'court' => 'required|string',
            'demand_value' => 'required|numeric',
            'fees_value' => 'required|numeric'
        ]);

        try {

            $count = Process::count() + 1;

            $process = Process::create([
                'lawyer_id' => $request->lawyer_id,
                'credit_id' => $request->credit_id,
                'code' => 'P' . time() . '-' . $count,
                'court' => $request->court,
                'demand_value' => $request->demand_value,
                'fees_value' => $request->fees_value,
                'payment' => $request->demand_value
            ]);

            return response()->json([
                'message' => 'Proceso creado correctamente',
                'process' => $process
            ], 201);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], 409);
        }
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'process_id' => 'required|integer|exists:processes,id',
            'value' => 'required|numeric'
        ]);

        try {

            $process = Process::find($request->process_id);

            if ($process->payment > 0) {

                $credit = Credit::findOrFail($process->credit_id);

                $process->payment = $process->payment - $request->value;
                if ($process->payment <= 0) {
                    $process->status = 'F';
                    $process->end_date = date('Y-m-d');

                }
                $process->save();
                $process->refresh();

                AccountService::updateAccount($credit->account, $request->value, 'add');
                StoreTransaction::dispatchSync($credit->account->id, 'process_payment', $request->value,
                    'Abono de procceso #' . $process->code, 3, 4, null, $process->id);


                $payment = number_format(($process->payment), 2, '.', ',');

                return response()->json(['message' => 'Valor abonado al proceso #' . $process->code . ' saldo restante: ' . $payment,
                    'process' => $process]);
            } else {
                $process->status = 'F';
                $process->save();
                $process->refresh();
                return response()->json(['message' => 'No se puede abonar a un proceso finalizado'], Response::HTTP_BAD_REQUEST);
            }

        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()]);
        }
    }

    public function show(Process $process)
    {
        $process = Process::with([
            'lawyer', 'credit', 'credit.debtor', 'credit.first_co_debtor', 'credit.second_co_debtor'
        ])->where('id', $process->id)->firstOrFail();

        return response()->json(['process' => $process]);
    }
}
