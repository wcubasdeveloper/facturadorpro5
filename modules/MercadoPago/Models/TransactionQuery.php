<?php

namespace Modules\MercadoPago\Models;

use App\Models\Tenant\{
    ModelTenant,
};

class TransactionQuery extends ModelTenant
{

    protected $fillable = [
        'date',
        'time',
        'response',
        'transaction_id',
    ];

    
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    } 

    public function getResponseAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setResponseAttribute($value)
    {
        $this->attributes['response'] = (is_null($value))?null:json_encode($value);
    }

}
