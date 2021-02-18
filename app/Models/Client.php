<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
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

    protected $appends = ['sign_url', 'last_transactions'];


    public function getSignUrlAttribute()
    {
        return $this->sign ? asset('storage') . '/' . $this->sign : '';
    }

    public function credits()
    {
        return $this->hasMany(Credit::class, 'debtor_id');
    }

    public function credits_co_debtor()
    {
        return $this->hasMany(Credit::class, 'first_co_debtor');
    }

    public function credits_second_debtor()
    {
        return $this->hasMany(Credit::class, 'second_co_debtor');
    }

    public function scopeByName($query, $name)
    {
        if ($name) {
            return $query->where('name', 'ilike', '%' . $name . '%');
        }
    }

    public function scopeByDocument($query, $document)
    {
        if ($document) {
            return $query->where('document_number', 'like', '%' . $document . '%');
        }
    }

    public function scopeByNameOrDocument($query, $value)
    {
        if ($value) {
            return $query->where('document_number', 'like', '%' . $value . '%')->orWhere('name', 'ilike', '%' . $value . '%');
        }
    }

    public function getLastTransactionsAttribute($value)
    {

        return Transaction::whereHas('credit', function (Builder $query) {
            $query->where('debtor_id', '=', $this->id)
            ->orWhere('first_co_debtor', '=', $this->id)
            ->orWhere('second_co_debtor', '=', $this->id);
        })->take(5)->get();
    }

}
