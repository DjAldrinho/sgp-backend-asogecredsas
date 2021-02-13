<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'document_type', 'document_number', 'sign', 'client_type', 'status',
        'position', 'salary', 'start_date', 'bonding'
    ];

    protected $casts = [
        'client_type' => 'object',
        'start_date' => 'date'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'deleted_at'
    ];

    protected $appends = ['sign_url'];


    public function getSignUrlAttribute()
    {
        return $this->sign ? asset('storage') . '/'. $this->sign : '';
    }
}
