<?php

namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\TechnicalServiceItem;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class SystemIscType extends ModelCatalog
{
    use UsesTenantConnection;

    protected $table = "cat_system_isc_types";
    public $incrementing = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public  function technical_service_item()
    {
        return $this->hasMany(TechnicalServiceItem::class, 'system_isc_type_id');
    }
}
