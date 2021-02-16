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
        'commission', 'fee', 'adviser_id', 'refinanced_id', 'status', 'account_id', 'commentary'
    ];

    protected $appends = ['liquidate'];

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
}
