<?php

namespace App\Models\Tenant;

use App\Models\Tenant\GuideFile;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Traits\SellerIdTrait;
use Illuminate\Support\Collection;
use Modules\Order\Models\OrderNote;
use Modules\Sale\Models\SaleOpportunity;
use Modules\Sale\Models\QuotationPayment;
use Modules\Sale\Models\Contract;

class Quotation extends ModelTenant
{
    use SellerIdTrait;

    protected $with = ['user', 'soap_type', 'state_type', 'currency_type', 'items', 'payments'];

    protected $fillable = [
        'id',
        'user_id',
        'external_id',
        'establishment_id',
        'establishment',
        'soap_type_id',
        'state_type_id',
        'payment_method_type_id',

        'prefix',

        'date_of_issue',
        'time_of_issue',
        'date_of_due',
        'delivery_date',
        'customer_id',
        'customer',
        'currency_type_id',
        'exchange_rate_sale',
        'total_prepayment',
        'total_discount',
        'total_charge',
        'total_exportation',
        'total_free',
        'total_taxed',
        'total_unaffected',
        'total_exonerated',
        'total_igv',
        'total_base_isc',
        'total_isc',
        'total_base_other_taxes',
        'total_other_taxes',
        'total_taxes',
        'total_value',
        'total',
        'charges',
        'discounts',
        'prepayments',
        'guides',
        'related',
        'perception',
        'detraction',
        'legends',
        'filename',
        'shipping_address',
        'description',
        'sale_opportunity_id',
        'changed',
        'account_number',
        'terms_condition',
        'referential_information',
        'contact',
        'phone',
        'seller_id',
        'total_igv_free',
        'subtotal',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function (self $model) {
            self::adjustSellerIdField($model);
        });

    }
    protected $casts = [
        'date_of_issue' => 'date',
        // 'date_of_due' => 'date',
        // 'delivery_date' => 'date',
    ];

    public function getEstablishmentAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setEstablishmentAttribute($value)
    {
        $this->attributes['establishment'] = (is_null($value))?null:json_encode($value);
    }

    public function getCustomerAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setCustomerAttribute($value)
    {
        $this->attributes['customer'] = (is_null($value))?null:json_encode($value);
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

    public function getPrepaymentsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setPrepaymentsAttribute($value)
    {
        $this->attributes['prepayments'] = (is_null($value))?null:json_encode($value);
    }

    public function getGuidesAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setGuidesAttribute($value)
    {
        $this->attributes['guides'] = (is_null($value))?null:json_encode($value);
    }

    public function getRelatedAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setRelatedAttribute($value)
    {
        $this->attributes['related'] = (is_null($value))?null:json_encode($value);
    }

    public function getPerceptionAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setPerceptionAttribute($value)
    {
        $this->attributes['perception'] = (is_null($value))?null:json_encode($value);
    }

    public function getDetractionAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setDetractionAttribute($value)
    {
        $this->attributes['detraction'] = (is_null($value))?null:json_encode($value);
    }

    public function getLegendsAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setLegendsAttribute($value)
    {
        $this->attributes['legends'] = (is_null($value))?null:json_encode($value);
    }

    public function getIdentifierAttribute()
    {
        return $this->prefix.'-'.$this->id;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function soap_type()
    {
        return $this->belongsTo(SoapType::class);
    }

    public function state_type()
    {
        return $this->belongsTo(StateType::class);
    }

    public function person() {
        return $this->belongsTo(Person::class, 'customer_id');
    }


    public function currency_type()
    {
        return $this->belongsTo(CurrencyType::class, 'currency_type_id');
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }


    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function sale_notes()
    {
        return $this->hasMany(SaleNote::class);
    }

    public function payment_method_type()
    {
        return $this->belongsTo(PaymentMethodType::class);
    }

    public function getNumberToLetterAttribute()
    {
        $legends = $this->legends;
        $legend = collect($legends)->where('code', '1000')->first();
        return $legend->value;
    }

    public function scopeWhereTypeUser($query, $params= [])
    {
        if(isset($params['user_id'])) {
            $user_id = (int)$params['user_id'];
            $user = User::find($user_id);
            if(!$user) {
                $user = new User();
            }
        }
        else {
            $user = auth()->user();
        }
        return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
    }

    public function sale_opportunity()
    {
        return $this->belongsTo(SaleOpportunity::class);
    }

    public function getNumberFullAttribute()
    {
        return $this->prefix.'-'.$this->id;
    }

    public function scopeWhereStateTypeAccepted($query)
    {
        return $query->whereIn('state_type_id', ['01']);
    }

    public function payments()
    {
        return $this->hasMany(QuotationPayment::class);
    }

    public function scopeWhereNotChanged($query)
    {
        return $query->where('changed', false);
    }

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id')->withDefault([
            'name' => ''
        ]);
    }

    /**
     * Devuelve datos standar para cotizacion.
     * Si $with_items es verdadero, devuelve los item de la cotizacion.
     * @param Company|null $company
     * @param Configuration|null $configuration
     * @param false $with_items
     * @return array
     */
    public function getCollectionData(Company $company = null, Configuration $configuration = null, $with_items = false){
        /** @var User $user */
        $user = auth()->user();
        if($company === null) {
            $company = Company::query()->first();
        }
        if($configuration === null) {
            $configuration = Configuration::query()->first();
        }

        $row = $this;
        $btn_generate = (count($row->documents) > 0 || count($row->sale_notes) > 0)?false:true;
        $btn_generate_cnt = $row->contract ?false:true;
        $external_id_contract = $row->contract ? $row->contract->external_id : null;

        $btn_options = ($row->state_type_id != '11') && $btn_generate && ($company->soap_type_id !== '03');
        if($user->type === 'seller') {
            $btn_options = $btn_options && ($configuration->quotation_allow_seller_generate_sale);
        } else {
            $btn_options = $btn_options && ($user->type === 'admin');
        }
        $items = new Collection();

        if($with_items === true) {
            $items = $row->items;
        }
        $orderNote = OrderNote::where('quotation_id',$this->id)->first();
        if($orderNote != null){
            $orderNote =[
              'id'=>$orderNote->id,
              'full_number'=>$orderNote->getNumberFullAttribute(),
            ];
        }else{
            $orderNote = [];
        }

        $seller = User::find($this->seller_id);
        if(empty($seller)){
            $seller = new User();
        }

        return [
            'id' => $row->id,
            'items' => $items,
            'order_note' => (object)$orderNote,
            'payment_method_type_id' => $row->payment_method_type_id,
            'soap_type_id' => $row->soap_type_id,
            'external_id' => $row->external_id,
            'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
            // 'delivery_date' => ($row->delivery_date) ? $row->delivery_date->format('Y-m-d') : null,
            'delivery_date' => $row->delivery_date,
            'identifier' => $row->identifier,
            'user_name' => $row->user->name,
            'seller_name' => $seller->name,
            'customer_id' => $row->customer_id,
            'customer_name' => $row->customer->name,
            'customer_number' => $row->customer->number,
            'currency_type_id' => $row->currency_type_id,
            'total_exportation' => number_format($row->total_exportation,2),
            'total_free' => number_format($row->total_free,2),
            'total_unaffected' => number_format($row->total_unaffected,2),
            'total_exonerated' => number_format($row->total_exonerated,2),
            'total_taxed' => number_format($row->total_taxed,2),
            'total_igv' => number_format($row->total_igv,2),
            'total' => number_format($row->total,2),
            'state_type_id' => $row->state_type_id,
            'state_type_description' => $row->state_type->description,
            'documents' => $row->documents->transform(function($row) {
                return [
                    'number_full' => $row->number_full,
                ];
            }),
            'sale_notes' => $row->sale_notes->transform(function($row) {
                return [
                    // 'identifier' => $row->identifier,
                    'number_full' => $row->number_full,
                ];
            }),
            'sale_opportunity_number_full' => ($row->sale_opportunity) ? $row->sale_opportunity->number_full:null,
            'contract_number_full' => ($row->contract) ? $row->contract->number_full:null,
            'sale_opportunity' => ($row->sale_opportunity) ? $row->sale_opportunity:null,
            'btn_generate' => $btn_generate,
            'btn_generate_cnt' => $btn_generate_cnt,
            'btn_options' => $btn_options,
            'external_id_contract' => $external_id_contract,
            'referential_information' => $row->referential_information,
            'created_at' => $row->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guide_files()
    {
        return $this->hasMany(GuideFile::class);
    }

    /**
     * Devuelve un standar para la fecha de entrega.
     *
     * Si es string devuelve dicho string. Contrario devolveria un formato de fecha
     *
     * @return mixed|string|null
     */
    public function getStringDeliveryDate(){

        if(empty($this->delivery_date)) {
            return null;
        }
        if(is_string($this->delivery_date)) {
            return $this->delivery_date;
        }

        return $this->delivery_date->format('Y-m-d');
    }


    /**
     *
     * Obtener total y realizar conversión al tipo de cambio si se requiere
     *
     * @return float
     */
    public function getTransformTotal()
    {
        return ($this->currency_type_id === 'PEN') ? $this->total : ($this->total * $this->exchange_rate_sale);
    }


    /**
     *
     * Validar si tiene estado registrado/aceptado
     *
     * @return bool
     */
    public function hasStateTypeAccepted()
    {
        return in_array($this->state_type_id, ['01','05']);
    }


    /**
     *
     * Validar si la cotizacion tiene pagos
     *
     * @return bool
     */
    public function hasPayments()
    {
        return $this->payments->count() > 0;
    }


    /**
     *
     * Validar si cumple las condiciones para sumar a los ingresos de caja o mostrar en reporte (pos)
     *
     * @return bool
     */
    public function applyQuotationToCash()
    {
        return ($this->hasStateTypeAccepted() && $this->hasPayments() && !$this->changed);
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
        return $query->withOut(['user', 'soap_type', 'state_type', 'currency_type', 'items', 'payments']);
    }


    /**
     * 
     * Obtener descripción del tipo de documento
     *
     * @return string
     */
    public function getDocumentTypeDescription()
    {
        return 'COTIZACIÓN';
    }

    
    /**
     * 
     * Obtener pagos en efectivo
     *
     * @return Collection
     */
    public function getCashPayments()
    {
        return $this->payments()->whereFilterCashPayment()->get()->transform(function($row){{
            return $row->getRowResourceCashPayment();
        }});
    }
    
    
    /**
     * 
     * Validar si el registro esta rechazado o anulado
     * 
     * @return bool
     */
    public function isVoidedOrRejected()
    {
        return in_array($this->state_type_id, self::VOIDED_REJECTED_IDS);
    }

}
