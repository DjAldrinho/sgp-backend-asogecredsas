<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Credit;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'account' => 'integer|exists:accounts,id',
            'start_date' => 'date',
            'end_date' => 'date'
        ]);

        $now = Carbon::now();
        $firstDay = Carbon::now()->firstOfMonth();


        if ($request->start_date) {
            $start_date = $request->start_date;
        } else {
            $start_date = $firstDay->isoFormat('Y/MM/DD');
        }

        if ($request->end_date) {
            $end_date = $request->end_date;
        } else {
            $end_date = $now->isoFormat('Y/MM/DD');
        }


        $accounts = Account::sum('value');

        $account = $request->account_id;

        $deposit = Transaction::byAccount($account)
            ->byOrigin(['deposit', 'credit_payment'])
            ->byDate($start_date, $end_date)
            ->sum('value');

        $retire = Transaction::byAccount($account)
            ->byOrigin(['retire', 'commission', 'credit'])
            ->byDate($start_date, $end_date)
            ->sum('value');

        $pending_credits = Credit::byAccount($account)
            ->where('status', 'P')
            ->byDate($start_date, $end_date)
            ->count();


        $active_credits = Credit::byAccount($account)
            ->where('status', 'A')
            ->byDate($start_date, $end_date)
            ->count();


        $finish_credits = Credit::byAccount($account)
            ->where('status', 'F')
            ->byDate($start_date, $end_date)
            ->count();

        $total_credits = Credit::byAccount($account)
            ->where('status', '!=', 'C')
            ->byDate($start_date, $end_date)
            ->count();


        $ids_credit = Transaction::select('credit_id')
            ->byOrigin('credit_payment')
            ->byAccount($account)
            ->byDate($start_date, $end_date)
            ->whereNotNull('credit_id')
            ->distinct()
            ->pluck('credit_id')->all();

        $expired_credits = Credit::where('status', 'A')
            ->byAccount($account)
            ->byDate($start_date, $end_date)
            ->whereNotIn('id', $ids_credit)
            ->count();


        return [
            'period' => "$start_date - $end_date",
            'total_account' => number_format(($accounts), 2, '.', ','),
            'total_deposit' => number_format(($deposit), 2, '.', ','),
            'total_retire' => number_format(($retire), 2, '.', ','),
            'total_credits' => $total_credits,
            'pending_credits' => $pending_credits,
            'active_credits' => $active_credits,
            'finish_credits' => $finish_credits,
            'expired_credits' => $expired_credits
        ];
    }
}
