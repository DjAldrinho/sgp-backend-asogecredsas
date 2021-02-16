<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['document_file'];

    protected $hidden = [
        'deleted_at'
    ];

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }
}
