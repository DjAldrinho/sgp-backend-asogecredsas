<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'payroll_id', 'credit_type_id', 'debtor_id', 'first_co_debtor', 'second_co_debtor',
        'start_date', 'refinanced', 'capital_value', 'transport_value', 'other_value', 'interest',
        'commission', 'fee', 'adviser_id', 'refinanced_id', 'status', 'account_id'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
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
}
