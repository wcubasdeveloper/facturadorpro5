<?php

namespace Modules\Purchase\Models;

use App\Models\Tenant\ModelTenant;
use App\Traits\AttributePerItems;

class PurchaseQuotationItem extends ModelTenant
{
    use AttributePerItems;
    public $timestamps = false;

    protected $fillable = [
        'purchase_quotation_id',
        'item_id',
        'item',
        'quantity',
    ];

    public function getItemAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value))?null:json_encode($value);
    }

    public function purchase_quotation()
    {
        return $this->belongsTo(PurchaseQuotation::class);
    }
}
