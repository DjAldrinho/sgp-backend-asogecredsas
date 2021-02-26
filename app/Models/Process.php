<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Process extends Model
{
    use HasFactory;

    protected $fillable = [
        'lawyer_id',
        'code',
        'credit_id',
        'court',
        'demand_value',
        'fees_value',
        'payment',
        'end_date',
        'status'
    ];

    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }

    public function scopeByCredit($query, $value)
    {
        if ($value) {
            return $query->where('credit_id', $value);
        }
    }

    public function scopeByLawyer($query, $value)
    {
        if ($value) {
            return $query->where('lawyer_id', $value);
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

    public function scopeByStatus($query, $status)
    {
        if ($status) {
            return $query->whereIn('status', $status);
        }
    }
}
