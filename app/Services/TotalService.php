<?php


namespace App\Services;


use App\Models\Account;
use App\Models\Credit;
use App\Models\Transaction;

class TotalService
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

    public function getTotalAccounts()
    {
        return Account::byStatus('A')->sum('value');
    }

    public function getTotalDeposit($account = null)
    {
        return Transaction::byAccount($account)
            ->byOrigin(['deposit', 'credit_payment'])
            ->byDate($this->start_date, $this->end_date)
            ->sum('value');
    }

    public function getTotalRetire($account = null)
    {
        return Transaction::byAccount($account)
            ->byOrigin(['retire', 'commission', 'credit'])
            ->byDate($this->start_date, $this->end_date)
            ->sum('value');
    }

    public function getTotalCredits($account = null)
    {
        $capital_value = Credit::byAccount($account)
            ->byStatus(['A', 'F'])
            ->byDate($this->start_date, $this->end_date)
            ->sum('capital_value');

        $transport_value = Credit::byAccount($account)
            ->byStatus(['A', 'F'])
            ->byDate($this->start_date, $this->end_date)
            ->sum('transport_value');

        $other_value = Credit::byAccount($account)
            ->byStatus(['A', 'F'])
            ->byDate($this->start_date, $this->end_date)
            ->sum('other_value');

        return $capital_value + $transport_value + $other_value;
    }

}
