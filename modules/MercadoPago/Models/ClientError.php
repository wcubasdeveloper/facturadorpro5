<?php

namespace Modules\MercadoPago\Models;

use App\Models\Tenant\{
    ModelTenant,
};

class ClientError extends ModelTenant
{

    public $timestamps = false;

    protected $fillable = [
        'code',
        'client_error_type_id',
        'original_message',
        'user_message',
    ];
  

    public function client_error_type()
    {
        return $this->belongsTo(ClientErrorType::class, 'client_error_type_id');
    } 

}
