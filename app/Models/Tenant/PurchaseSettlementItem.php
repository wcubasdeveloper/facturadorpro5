<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\{
    AffectationIgvType,
    PriceType
};


class PurchaseSettlementItem extends ModelTenant
{
    
    public $timestamps = false;

    protected $fillable = [
        'purchase_settlement_id',
        'item_id',
        'item',
        'quantity',
        'unit_value',

        'affectation_igv_type_id',
        'total_base_igv',
        'percentage_igv',
        'total_igv',

        'total_taxes',

        'price_type_id',
        'unit_price',

        'total_value',
        'total',

        'income_tax_affectation_igv_type_id',
        'income_retention_percentage',
        'income_retention_amount',

    ];

    public function getItemAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value))?null:json_encode($value);
    }

    public function affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'affectation_igv_type_id');
    }

    public function income_tax_affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'income_tax_affectation_igv_type_id');
    }

    public function price_type()
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }
}