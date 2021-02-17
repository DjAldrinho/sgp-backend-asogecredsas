<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'value', 'status'];

    protected $hidden = ['deleted_at '];

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }
}
