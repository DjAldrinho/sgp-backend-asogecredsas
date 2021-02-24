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
        'credits_paginate', 'deposits', 'retires'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function getCreditsPaginateAttribute($value)
    {
        return Credit::where('account_id', $this->id)
            ->orderBy('created_at', 'desc')
            ->paginate(5);

    }

    public function getDepositsAttribute($value)
    {
        $deposits = Transaction::byOrigin(['deposit', 'credit_payment'])
            ->byAccount($this->id);

        return [
            'total' => number_format($deposits->sum('value'), 2, '.', ','),
            'detail' => $deposits->orderBy('created_at', 'desc')->paginate(5)
        ];
    }

    public function getRetiresAttribute($value)
    {
        $retires = Transaction::byOrigin(['retire', 'commission', 'credit'])
            ->byAccount($this->id);

        return [
            'total' => number_format($retires->sum('value'), 2, '.', ','),
            'detail' => $retires->orderBy('created_at', 'desc')->paginate(5)
        ];
    }


}
