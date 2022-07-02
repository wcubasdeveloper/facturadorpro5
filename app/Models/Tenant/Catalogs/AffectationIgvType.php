<?php

namespace App\Models\Tenant\Catalogs;

use App\Models\Tenant\TechnicalServiceItem;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class AffectationIgvType extends ModelCatalog
{
    use UsesTenantConnection;

    protected $table = "cat_affectation_igv_types";
    public $incrementing = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public  function technical_service_item()
    {
        return $this->hasMany(TechnicalServiceItem::class, 'affectation_igv_type_id');
    }
}
