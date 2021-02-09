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

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}