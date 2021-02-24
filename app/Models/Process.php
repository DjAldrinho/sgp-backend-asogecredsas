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
}
