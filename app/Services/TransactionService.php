<?php


namespace App\Services;


use App\Models\Transaction;

class TransactionService
{
    public function getTransactions($request, $withRelations = false)
    {
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
            ->byProcess($request->process)
            ->byClient($request->client)
            ->byAdviser($request->adviser);

        if ($withRelations) {
            $transactions = $transactions->with(['credit', 'credit.debtor', 'user', 'account', 'type_transaction']);
        }


        return $transactions->orderBy('created_at', 'desc');

    }
}
