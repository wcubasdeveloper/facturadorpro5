<?php

namespace Modules\MercadoPago\Models;

use App\Models\Tenant\{
    ModelTenant,
};


class ClientErrorType extends ModelTenant
{

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id',
        'name',
    ];
    
}
