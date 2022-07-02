<?php

namespace App\Models\Tenant\Catalogs;

use Hyn\Tenancy\Traits\UsesTenantConnection;

class AddressType extends ModelCatalog
{
    use UsesTenantConnection;

    protected $table = 'cat_address_types';

    public $incrementing = false;
    public $timestamps = false;
}