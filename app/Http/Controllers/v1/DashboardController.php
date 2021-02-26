<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Services\CountService;
use App\Services\TotalService;
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

        $totalService = new TotalService($start_date, $end_date);
        $countService = new CountService($start_date, $end_date);

        $account = $request->account_id;


        return [
            'period' => "$start_date - $end_date",
            'total_account' => number_format(($totalService->getTotalAccounts()), 2, '.', ','),
            'total_deposit' => number_format(($totalService->getTotalDeposit($account)), 2, '.', ','),
            'total_retire' => number_format(($totalService->getTotalRetire($account)), 2, '.', ','),
            'total_credits' => $countService->getTotalCountCredits($account),
            'pending_credits' => $countService->getTotalCountPendingCredits($account),
            'active_credits' => $countService->getTotalCountActiveCredits($account),
            'finish_credits' => $countService->getTotalCountFinishCredits($account),
            'expired_credits' => $countService->getTotalCountExpiredCredits($account)->count(),
        ];
    }
}
