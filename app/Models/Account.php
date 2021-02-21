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
        'info'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function getInfoAttribute($value)
    {

        $deposits = Transaction::byOrigin(['deposit', 'credit_payment'])
            ->byAccount($this->id);

        $retires = Transaction::byOrigin(['retire', 'commission', 'credit'])
            ->byAccount($this->id);

        return [
            'deposits' => [
                'total' => number_format($deposits->sum('value'), 2, '.', ','),
                'detail' => $deposits->paginate(5)
            ],
            'retires' => [
                'total' => number_format($retires->sum('value'), 2, '.', ','),
                'detail' => $retires->paginate(5)
            ]
        ];
    }

}
