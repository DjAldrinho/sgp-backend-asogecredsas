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

        $now = Carbon::now();
        $firstDay = Carbon::now()->firstOfMonth();
        $period = $firstDay->isoFormat('D/M/Y') . ' - ' . $now->isoFormat('D/M/Y');

        $accounts = Account::sum('value');

        $account = $request->account_id;

        $deposit = Transaction::byAccount($account)
            ->byOrigin(['deposit', 'credit_payment'])
            ->whereRaw("EXTRACT(MONTH FROM created_at) = {$now->isoFormat('M')}")
            ->sum('value');

        $retire = Transaction::byAccount($account)
            ->byOrigin(['retire', 'commission', 'credit'])
            ->whereRaw("EXTRACT(MONTH FROM created_at) = {$now->isoFormat('M')}")
            ->sum('value');

        $pending_credits = Credit::byAccount($account)
            ->where('status', 'P')
            ->whereRaw("EXTRACT(MONTH FROM created_at) = {$now->isoFormat('M')}")
            ->count();


        $active_credits = Credit::byAccount($account)
            ->where('status', 'A')
            ->whereRaw("EXTRACT(MONTH FROM created_at) = {$now->isoFormat('M')}")
            ->count();


        $finish_credits = Credit::byAccount($account)
            ->where('status', 'F')
            ->whereRaw("EXTRACT(MONTH FROM created_at) = {$now->isoFormat('M')}")
            ->count();

        $total_credits = Credit::byAccount($account)
            ->where('status', '!=', 'C')
            ->whereRaw("EXTRACT(MONTH FROM created_at) = {$now->isoFormat('M')}")
            ->count();


        $ids_credit = Transaction::select('credit_id')->byOrigin('credit_payment')
            ->byAccount($account)
            ->whereNotNull('credit_id')
            ->distinct()
            ->pluck('credit_id')->all();

        $expired_credits = Credit::where('status', 'A')
            ->byAccount($account)
            ->whereRaw("EXTRACT(MONTH FROM created_at) = {$now->isoFormat('M')}")
            ->whereNotIn('id', $ids_credit)
            ->count();


        return [
            'period' => $period,
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
