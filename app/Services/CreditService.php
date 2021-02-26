<?php


namespace App\Services;


use App\Models\Credit;
use Illuminate\Http\Request;

class CreditService extends Service
{

    public function __constructor()
    {
        parent::__constructor();
    }

    public function getCredits(Request $request)
    {
        $status = null;

        if ($request->status) {
            $status = explode(',', $request->status);
        }

        return Credit::with(
            [
                'transactions', 'account', 'documents', 'debtor', 'first_co_debtor', 'second_co_debtor', 'adviser',
                'credit_type', 'payroll', 'credit_refinanced'
            ])->byAccount($request->account)
            ->byClient($request->client)
            ->byFirstCoDebtor($request->first_co_debtor)
            ->bySecondCoDebtor($request->second_co_debtor)
            ->byAdviser($request->adviser)
            ->byDate($request->start_date, $request->end_date)
            ->byStatus($status)
            ->orderBy('created_at', 'desc');
    }
}
