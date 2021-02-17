<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'type_transaction_id',
        'origin',
        'code',
        'supplier_id',
        'value',
        'user_id',
        'commentary'
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function type_transaction()
    {
        return $this->belongsTo(TypeTransaction::class);
    }

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByAccount($query, $account)
    {
        if ($account) {
            return $query->where('account_id', '=', $account);
        }
    }

    public function scopeOrigin($query, $origin)
    {
        if ($origin) {
            return $query->where('origin', '=', $origin);
        }
    }

}
