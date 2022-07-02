<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Traits\AttributePerItems;
use Modules\Inventory\Models\Warehouse;
use Modules\Item\Models\ItemLot;

/**
 * App\Models\Tenant\PurchaseItem
 *
 * @property-read AffectationIgvType $affectation_igv_type
 * @property mixed $attributes
 * @property mixed $charges
 * @property mixed $discounts
 * @property \App\Models\Tenant\Item $item
 * @property-read \Illuminate\Database\Eloquent\Collection|ItemLot[] $lots
 * @property-read int|null $lots_count
 * @property-read PriceType $price_type
 * @property-read \App\Models\Tenant\Purchase $purchase
 * @property-read \App\Models\Tenant\Item $relation_item
 * @property-read SystemIscType $system_isc_type
 * @property-read Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PurchaseItem query()
 * @mixin \Eloquent
 */
class PurchaseItem extends ModelTenant
{
    use AttributePerItems;
    protected $with = ['affectation_igv_type', 'system_isc_type', 'price_type', 'lots', 'warehouse'];
    public $timestamps = false;

    protected $fillable = [
        'purchase_id',
        'item_id',
        'item',
        'quantity',
        'unit_value',

        'affectation_igv_type_id',
        'total_base_igv',
        'percentage_igv',
        'total_igv',

        'system_isc_type_id',
        'total_base_isc',
        'percentage_isc',
        'total_isc',

        'total_base_other_taxes',
        'percentage_other_taxes',
        'total_other_taxes',
        'total_taxes',

        'price_type_id',
        'unit_price',

        'total_value',
        'total',

        'attributes',
        'charges',
        'lot_code',
        'warehouse_id',
        'discounts',
        'date_of_due'
    ];



    public function getItemAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value))?null:json_encode($value);
    }

    public function getAttributesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setAttributesAttribute($value)
    {
        $this->attributes['attributes'] = (is_null($value))?null:json_encode($value);
    }

    public function getChargesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setChargesAttribute($value)
    {
        $this->attributes['charges'] = (is_null($value))?null:json_encode($value);
    }

    public function getDiscountsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDiscountsAttribute($value)
    {
        $this->attributes['discounts'] = (is_null($value))?null:json_encode($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'affectation_igv_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function system_isc_type()
    {
        return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function price_type()
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function lots()
    {
        return $this->morphMany(ItemLot::class, 'item_loteable');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relation_item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function getCollectionData(Configuration $configuration = null) {

        if(empty($configuration)){
            $configuration =  Configuration::first();
        }
        $purchase = $this->purchase;
        $purchase_collection = $purchase->getCollectionData();

        $item = $this->item;
         $wharehouse = $this->warehouse;
         $date_of_due = "-";
         if($this->date_of_due){
             if(is_string($this->date_of_due)){
                 $date_of_due = $this->date_of_due;
             }else{
                 $date_of_due =$this->date_of_due->format('Y-m-d');
             }
         }

        $data = [

            /*
                            <template v-if="type == 'sale'">
                                row.total_item_purchase
                                row.utility_item
                            </template>
        */
            'id'                      => $this->id,
            'purchase'                => $purchase_collection,
            'purchase_id'             => $this->purchase_id,
            'item_id'                 => $this->item_id,
            // 'item'                    => $this->item,
            'item' => $item,
            'quantity'                => number_format($this->quantity,2,'.',''),
            'unit_value'              => $this->unit_value,
            'affectation_igv_type_id' => $this->affectation_igv_type_id,
            'total_base_igv'          => $this->total_base_igv,
            'percentage_igv'          => $this->percentage_igv,
            'total_igv'               => $this->total_igv,
            'system_isc_type_id'      => $this->system_isc_type_id,
            'total_base_isc'          => $this->total_base_isc,
            'percentage_isc'          => $this->percentage_isc,
            'total_isc'               => $this->total_isc,
            'total_base_other_taxes'  => $this->total_base_other_taxes,
            'percentage_other_taxes'  => $this->percentage_other_taxes,
            'total_other_taxes'       => $this->total_other_taxes,
            'total_taxes'             => $this->total_taxes,
            'price_type_id'           => $this->price_type_id,
            'unit_price'              => $this->unit_price,
            'total_value'             => $this->total_value,
            'total'                   => $this->total,
            'attributes'              => $this->attributes,
            'charges'                 => $this->charges,
            'lot_code'                => $this->lot_code,
            'warehouse_id'            => $this->warehouse_id,
            'warehouse'            => $wharehouse,
            'discounts'               => $this->discounts,
            'date_of_due' => $date_of_due,

        ];

         self::returnValuesToCollection($data,$purchase_collection,'customer_number');
         self::returnValuesToCollection($data,$purchase_collection,'customer_name');
         self::returnValuesToCollection($data,$purchase_collection,'date_of_issue');
         self::returnValuesToCollection($data,$purchase_collection,'document_type_description');
         self::returnValuesToCollection($data,$purchase_collection,'series');
         self::returnValuesToCollection($data,$purchase_collection,'alone_number');
         self::returnValuesToCollection($data,$purchase_collection,'internal_id');
         self::returnValuesToCollection($data,$purchase_collection,'brand');
         self::returnValuesToCollection($data,$purchase_collection,'description');
         self::returnValuesToCollection($data,$purchase_collection,'lot_has_sale');
         self::returnValuesToCollection($data,$purchase_collection,'web_platform_name');
         self::returnValuesToCollection($data,$purchase_collection,'unit_value');
         self::returnValuesToCollection($data,$purchase_collection,'currency_type_id');

        return $data;
    }
    protected static function returnValuesToCollection($array,$purchase,$index){
        if (!isset($array[$index]) && isset($purchase[$index])) {
            $array['index'] = $purchase[$index];
        } elseif (!isset($purchase[$index]) && !isset($array[$index])) {
            $array[$index] = null;
        }
    }

    /**
     * @return Item|Item[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|null
     */
    public function getModelItem(){ return Item::find($this->item_id);}

    
    /**
     * Validar si es compra en dolares
     *
     * @return bool
     */
    public function isCurrencyTypeUsd()
    {
        return $this->purchase->currency_type_id === 'USD';
    }

    /**
     * 
     * Obtener total y realizar conversión a soles de acuerdo al tipo de cambio
     *
     * @return float
     */
    public function getConvertTotalToPen()
    {
        return $this->generalConvertValueToPen($this->total, $this->purchase->exchange_rate_sale);
    }

    /**
     * 
     * Obtener valor unitario y realizar conversión a soles de acuerdo al tipo de cambio
     *
     * @return float
     */
    public function getConvertUnitValueToPen()
    {
        return $this->generalConvertValueToPen($this->unit_value, $this->purchase->exchange_rate_sale);
    }
    
    /**
     * 
     * Obtener precio unitario y realizar conversión a soles de acuerdo al tipo de cambio
     *
     * @return float
     */
    public function getConvertUnitPriceToPen()
    {
        return $this->generalConvertValueToPen($this->unit_price, $this->purchase->exchange_rate_sale);
    }

    /**
     * 
     * Obtener total valor y realizar conversión a soles de acuerdo al tipo de cambio
     *
     * @return float
     */
    public function getConvertTotalValueToPen()
    {
        return $this->generalConvertValueToPen($this->total_value, $this->purchase->exchange_rate_sale);
    }

    /**
     * 
     * Obtener total igv y realizar conversión a soles de acuerdo al tipo de cambio
     *
     * @return float
     */
    public function getConvertTotalIgvToPen()
    {
        return $this->generalConvertValueToPen($this->total_igv, $this->purchase->exchange_rate_sale);
    }

    /**
     * 
     * Obtener total isc y realizar conversión a soles de acuerdo al tipo de cambio
     *
     * @return float
     */
    public function getConvertTotalIscToPen()
    {
        return $this->generalConvertValueToPen($this->total_isc, $this->purchase->exchange_rate_sale);
    }


}
