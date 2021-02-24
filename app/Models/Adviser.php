<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adviser extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'phone', 'status'];

    protected $appends = [
        'credits_paginate', 'total_commissions'
    ];

    protected $hidden = ['deleted_at'];

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function scopeByNameOrPhone($query, $value)
    {
        if ($value) {
            return $query->where('phone', 'like', '%' . $value . '%')->orWhere('name', 'ilike', '%' . $value . '%');
        }
    }

    public function getCreditsPaginateAttribute($value)
    {
        return Credit::where('adviser_id', $this->id)
            ->paginate(5);
    }

    public function getTotalCommissionsAttribute($value)
    {
        $total = 0;

        $credits = Credit::where('adviser_id', $this->id)
            ->where('commission', '>', 0)
            ->get();

        if (count($credits) > 0) {
            foreach ($credits as $credit) {
                $total += ($credit->capital_value + $credit->transport_value + $credit->other_value) * ($credit->commission / 100);
            }
        }

        return number_format($total, 2, '.', ',');;
    }


}
