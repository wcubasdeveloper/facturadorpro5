<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class ExchangeRate extends ModelTenant
{
    use UsesTenantConnection;

    protected $fillable = [
        'date',
        'date_original',
        'purchase',
        'purchase_original',
        'sale',
        'sale_original',
    ];

    protected $casts = [
        'purchase' => 'float',
        'purchase_original' => 'float',
        'sale' => 'float',
        'sale_original' => 'float',
    ];
}
