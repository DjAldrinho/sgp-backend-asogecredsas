<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'account_id',
        'type_transaction_id',
        'origin',
        'code',
        'supplier_id',
        'value',
        'user_id',
        'commentary',
        'credit_id',
        'process_id'
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

    public function scopeByOrigin($query, $origin)
    {
        if ($origin) {
            if (!is_array($origin)) {
                $origin = [$origin];
            }

            return $query->whereIn('origin', $origin);
        }
    }

    public function scopeByCredit($query, $credit)
    {
        if ($credit) {
            return $query->where('credit_id', '=', $credit);
        }
    }

    public function scopeByUser($query, $user)
    {
        if ($user) {
            return $query->where('user_id', '=', $user);
        }
    }

    public function scopeBySupplier($query, $supplier)
    {
        if ($supplier) {
            return $query->where('supplier_id', '=', $supplier);
        }
    }

    public function scopeByTypeTransaction($query, $typeTransaction)
    {
        if ($typeTransaction) {
            return $query->where('type_transaction_id', '=', $typeTransaction);
        }
    }

    public function scopeByDate($query, $start_date, $end_date = null)
    {
        if ($start_date) {
            $query->where('created_at', '>=', "$start_date 00:00:00");
        }

        if ($end_date) {
            $query->where('created_at', '<=', "$end_date 23:59:59");
        }

        return $query;
    }

    public function scopeByProcess($query, $process)
    {
        if ($process) {
            return $query->where('process_id', $process);
        }
    }

    public function scopeByClient($query, $client)
    {
        if ($client) {
            return $query->whereHas('credit', function (Builder $query) use ($client) {
                $query->where('debtor_id', $client);
            });
        }
    }

    public function scopeByAdviser($query, $adviser)
    {
        if ($adviser) {
            return $query->whereHas('credit', function (Builder $query) use ($adviser) {
                $query->where('adviser_id', $adviser);
            });
        }
    }
}
