<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'old_value',
        'value',
        'account_number',
        'status'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected $appends = [
        'total_deposits', 'total_retires'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function getTotalDepositsAttribute($value)
    {
        $deposits = Transaction::byOrigin(['deposit', 'credit_payment'])
            ->byAccount($this->id);

        return number_format($deposits->sum('value'), 2, '.', ',');
    }

    public function getTotalRetiresAttribute($value)
    {
        $retires = Transaction::byOrigin(['retire', 'commission', 'credit'])
            ->byAccount($this->id);

        return number_format($retires->sum('value'), 2, '.', ',');
    }
}
