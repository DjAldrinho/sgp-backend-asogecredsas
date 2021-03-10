<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'status'
    ];

    protected $hidden = [
        'deleted_at'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function setOldValueAttribute($value)
    {
        $this->attributes['old_value'] = $this->value;
    }

    public function scopeByName($query, $value)
    {
        if ($value) {
            return $query->Where('name', 'ilike', '%' . $value . '%');
        }
    }
}
