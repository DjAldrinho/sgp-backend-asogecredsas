<?php

namespace App\Models;

use App\Helpers\CreditHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'payroll_id', 'credit_type_id', 'debtor_id', 'first_co_debtor', 'second_co_debtor',
        'start_date', 'refinanced', 'capital_value', 'transport_value', 'other_value', 'interest',
        'commission', 'fee', 'adviser_id', 'refinanced_id', 'status', 'account_id', 'commentary', 'payment'
    ];

    protected $appends = ['liquidate', 'totals'];

    protected $casts = [
        'payment' => 'decimal:2'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function documents()
    {
        return $this->hasMany(CreditDocument::class);
    }

    public function debtor()
    {
        return $this->belongsTo(Client::class, 'debtor_id');
    }

    public function first_co_debtor()
    {
        return $this->belongsTo(Client::class, 'first_co_debtor');
    }

    public function second_co_debtor()
    {
        return $this->belongsTo(Client::class, 'second_co_debtor');
    }

    public function adviser()
    {
        return $this->belongsTo(Adviser::class);
    }

    public function credit_refinanced()
    {
        return $this->belongsTo(Credit::class, 'refinanced_id');
    }

    public function credit_type()
    {
        return $this->belongsTo(CreditType::class);
    }

    public function payroll()
    {
        return $this->belongsTo(Payroll::class);
    }


    public function scopeByAccount($query, $account)
    {
        if ($account) {
            return $query->where('account_id', '=', $account);
        }
    }

    public function scopeByClient($query, $client)
    {
        if ($client) {
            return $query->where('debtor_id', '=', $client)
                ->orWhere('first_co_debtor', '=', $client)
                ->orWhere('second_co_debtor', '=', $client);
        }
    }

    public function getLiquidateAttribute($value)
    {
        $data = [
            "interest" => $this->interest,
            "other_value" => $this->other_value,
            "transport_value" => $this->transport_value,
            "capital_value" => $this->capital_value,
            "fee" => $this->fee,
            "start_date" => $this->start_date
        ];

        return CreditHelper::liquidate($data);
    }

    public function getTotalsAttribute($value)
    {
        $deposit = Transaction::byCredit($this->id)
            ->byOrigin(['credit_payment']);


        $retire = Transaction::byCredit($this->id)
            ->byOrigin(['commission', 'credit']);

        return [
            'total_deposit' => $deposit->sum('value'),
            'total_retires' => $retire->sum('value'),
            'deposits' => $deposit->paginate(5),
            'retires' => $retire->paginate(5)
        ];
    }

    public function scopeByDate($query, $start_date, $end_date = null)
    {
        if ($start_date) {
            $query->where('created_at', '>=', "$start_date 00:00:00");
        }

        if ($end_date) {
            $query->where('created_at', '<=', "$end_date 23:59:00");
        }

        return $query;
    }

    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
    }
}
