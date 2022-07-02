<?php

namespace App\Models\Tenant;

use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Traits\AttributePerItems;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Inventory\Models\Warehouse;

class SaleNoteItem extends ModelTenant
{
    use AttributePerItems;
    protected $with = ['affectation_igv_type', 'system_isc_type', 'price_type'];
    public $timestamps = false;

    protected $fillable = [
        'sale_note_id',
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
        'total_charge',
        'total_discount',
        'total',

        'attributes',
        'charges',
        'discounts',
        'inventory_kardex_id',
        'warehouse_id',
        'total_plastic_bag_taxes',
        'additional_information',
        'name_product_pdf',

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

    public function affectation_igv_type()
    {
        return $this->belongsTo(AffectationIgvType::class, 'affectation_igv_type_id');
    }

    public function system_isc_type()
    {
        return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
    }

    public function price_type()
    {
        return $this->belongsTo(PriceType::class, 'price_type_id');
    }

    public function sale_note()
    {
        return $this->belongsTo(SaleNote::class, 'sale_note_id');
    }

    public function relation_item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * @param $query
     * @param $params
     *
     * @return Builder
     */
    public function scopeWhereDefaultDocumentType($query, $params)
    {

        $db_raw = DB::raw("sale_note_items.id as id, sale_notes.series as series, sale_notes.number as number,
                            sale_note_items.item as item, sale_note_items.quantity as quantity,
                            sale_note_items.item_id as item_id,sale_notes.date_of_issue as date_of_issue");

        if (isset($params['establishment_id'])) {
            $query->where('establishment_id', $params['establishment_id']);
        }


        /**
         * Con este filtro se ajsuta reportes por campo user_id y seller_id para modulos que no lo tengan, esto es
         * si el envio de la peticion tiene user_type y user_id se considera qomo parte del filtro de creador/vendedor
         *
         * Este filtro es compartido por app/Models/Tenant/DocumentItem.php
         *
         * @var \Illuminate\Http\Request $rquest
         */
        $request = request();
        $userType = ($request !== null && $request->has('user_type')&& !empty($request->user_type))?$request->user_type:null;
        $userId =  ($request !== null && $request->has('user_id')&& !empty($request->user_id))?$request->user_id:null;

        $params['user_type'] = $userType;
        $params['user_id'] = $userId;



        $query->whereHas('sale_note', function ($q) use ($params) {
            $q->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']])
                ->whereStateTypeAccepted()
                ->whereTypeUser();
            if ($params['person_id']) {
                $q->where('customer_id', $params['person_id']);
            }
            if (
                !empty($params['user_type']) &&
                !empty($params['user_id'])
            ) {
                // Se ajusta las validaciones para determinar que viene por filtro de vendedor/creador
                $column = null;
                if($params['user_type'] == 'CREADOR'){
                    // Quien realiza el documento
                    $column =  'user_id';
                }elseif($params['user_type'] == 'VENDEDOR'){
                    // Vendedor asignado
                    $column =  'seller_id';
                }

                if($column !== null){
                    if(Str::startsWith($params['user_id'], '[')){
                        // Si comienza con [ es un array serialziado por json.
                        $usersId = json_decode($params['user_id']);
                        if (count($usersId) > 0) {
                            $q->whereIn($column, $usersId);
                        }
                    }else{
                        $q->where($column,  $params['user_id']);

                    }
                }

            }elseif (isset($params['sellers'])) {
                $sellers = json_decode($params['sellers']);
                if (count($sellers) > 0) {
                    $q->whereIn('user_id', $sellers);
                }
            }
        })
            ->join('sale_notes', 'sale_note_items.sale_note_id', '=', 'sale_notes.id')
            ->select($db_raw)
            ->latest('id');

        return $query;
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return Item|Item[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed|null
     */
    public function getModelItem(){ return Item::find($this->item_id);}

    
    /**
     * Validar si es venta en dolares
     *
     * @return bool
     */
    public function isCurrencyTypeUsd()
    {
        return $this->sale_note->currency_type_id === 'USD';
    }

    /**
     * 
     * Obtener total y realizar conversión a soles de acuerdo al tipo de cambio
     *
     * @return float
     */
    public function getConvertTotalToPen()
    {
        return $this->generalConvertValueToPen($this->total, $this->sale_note->exchange_rate_sale);
    }

    /**
     * 
     * Obtener valor unitario y realizar conversión a soles de acuerdo al tipo de cambio
     *
     * @return float
     */
    public function getConvertUnitValueToPen()
    {
        return $this->generalConvertValueToPen($this->unit_value, $this->sale_note->exchange_rate_sale);
    }

    /**
     * 
     * Obtener precio unitario y realizar conversión a soles de acuerdo al tipo de cambio
     *
     * @return float
     */
    public function getConvertUnitPriceToPen()
    {
        return $this->generalConvertValueToPen($this->unit_price, $this->sale_note->exchange_rate_sale);
    }

    /**
     * 
     * Obtener total valor y realizar conversión a soles de acuerdo al tipo de cambio
     *
     * @return float
     */
    public function getConvertTotalValueToPen()
    {
        return $this->generalConvertValueToPen($this->total_value, $this->sale_note->exchange_rate_sale);
    }
    
    /**
     * 
     * Obtener total igv y realizar conversión a soles de acuerdo al tipo de cambio
     *
     * @return float
     */
    public function getConvertTotalIgvToPen()
    {
        return $this->generalConvertValueToPen($this->total_igv, $this->sale_note->exchange_rate_sale);
    }
    
    /**
     * 
     * Obtener total isc y realizar conversión a soles de acuerdo al tipo de cambio
     *
     * @return float
     */
    public function getConvertTotalIscToPen()
    {
        return $this->generalConvertValueToPen($this->total_isc, $this->sale_note->exchange_rate_sale);
    }
    
    
    /**
     * 
     * Filtro para no incluir relaciones en consulta
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */  
    public function scopeWhereFilterWithOutRelations($query)
    {
        return $query->withOut(['affectation_igv_type', 'system_isc_type', 'price_type']);
    }


    /**
     * 
     * Filtro para reporte de ventas grifo
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */  
    public function scopeFilterSaleGarageGLL($query, $d_start, $d_end)
    {
        return $query->whereHas('relation_item', function($query){
                        return $query->where('unit_type_id' , 'GLL');
                    })
                    ->whereFilterWithOutRelations()
                    ->whereHas('sale_note', function($query) use($d_start, $d_end){
                        return $query->whereStateTypeAccepted()->filterRangeDateOfIssue($d_start, $d_end);
                    });
    }


}
