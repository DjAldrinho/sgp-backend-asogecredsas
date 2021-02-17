<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adviser extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'phone', 'status'];

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
}
