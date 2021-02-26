<?php


namespace App\Services;


use App\Models\Credit;
use App\Models\Transaction;

class CountService
{
    private $start_date, $end_date;

    /**
     * TotalService constructor.
     * @param $start_date
     * @param $end_date
     */
    public function __construct($start_date = null, $end_date = null)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }


    public function getTotalCountCredits($account = null)
    {
        return Credit::byAccount($account)
            ->where('status', '!=', 'C')
            ->byDate($this->start_date, $this->end_date)
            ->count();
    }

    public function getTotalCountExpiredCredits($account = null, $client = null, $first_co_debtor = null, $second_co_debtor = null)
    {
        $ids_credit = Transaction::select('credit_id')
            ->byOrigin('credit_payment')
            ->byAccount($account)
            ->byDate($this->start_date, $this->end_date)
            ->whereNotNull('credit_id')
            ->distinct()
            ->pluck('credit_id')->all();

        return Credit::where('status', 'A')
            ->byAccount($account)
            ->byDate($this->start_date, $this->end_date)
            ->byClient($client)
            ->byFirstCoDebtor($first_co_debtor)
            ->bySecondCoDebtor($second_co_debtor)
            ->whereNotIn('id', $ids_credit);
    }

    public function getTotalCountFinishCredits($account = null)
    {
        return Credit::byAccount($account)
            ->where('status', 'F')
            ->byDate($this->start_date, $this->end_date)
            ->count();
    }

    public function getTotalCountActiveCredits($account = null)
    {
        return Credit::byAccount($account)
            ->where('status', 'A')
            ->byDate($this->start_date, $this->end_date)
            ->count();
    }

    public function getTotalCountPendingCredits($account = null)
    {
        return Credit::byAccount($account)
            ->where('status', 'P')
            ->byDate($this->start_date, $this->end_date)
            ->count();
    }
}
