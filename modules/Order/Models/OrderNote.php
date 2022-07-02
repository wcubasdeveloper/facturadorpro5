<?php

    namespace Modules\Order\Models;

    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Dispatch;
    use App\Models\Tenant\Document;
    use App\Models\Tenant\GuideFile;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\PaymentMethodType;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\Quotation;
    use App\Models\Tenant\SaleNote;
    use App\Models\Tenant\SoapType;
    use App\Models\Tenant\StateType;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\MorphMany;
    use Illuminate\Support\Collection;
    use Modules\Inventory\Models\InventoryKardex;
    use App\Models\Tenant\Item;
    use Modules\Item\Models\ItemLot;
    use Modules\Item\Models\ItemLotsGroup;


    /**
     * Modules\Order\Models\OrderNote
     *
     * @package Modules\Order\Models
     * @mixin ModelTenant
     * @property int                                                        $id
     * @property int                                                        $user_id
     * @property string                                                     $external_id
     * @property int                                                        $establishment_id
     * @property string                                                     $soap_type_id
     * @property string                                                     $state_type_id
     * @property string                                                     $prefix
     * @property Carbon                                                     $date_of_issue
     * @property Carbon                                                     $time_of_issue
     * @property Carbon|null                                                $date_of_due
     * @property Carbon|null                                                $delivery_date
     * @property int                                                        $customer_id
     * @property string|null                                                $shipping_address
     * @property string                                                     $currency_type_id
     * @property string|null                                                $payment_method_type_id
     * @property float                                                      $exchange_rate_sale
     * @property float                                                      $total_prepayment
     * @property float                                                      $total_charge
     * @property float                                                      $total_discount
     * @property float                                                      $total_exportation
     * @property float                                                      $total_free
     * @property float                                                      $total_taxed
     * @property float                                                      $total_unaffected
     * @property float                                                      $total_exonerated
     * @property float                                                      $total_igv
     * @property float                                                      $total_igv_free
     * @property float                                                      $total_base_isc
     * @property float                                                      $total_isc
     * @property float                                                      $total_base_other_taxes
     * @property float                                                      $total_other_taxes
     * @property float                                                      $total_taxes
     * @property float                                                      $total_value
     * @property float                                                      $total
     * @property string|null                                                $filename
     * @property string|null                                                $observation
     * @property Carbon|null                                                $created_at
     * @property Carbon|null                                                $updated_at
     * @property int|null                                                   $quotation_id
     * @property CurrencyType                                               $currency_type
     * @property \Illuminate\Database\Eloquent\Collection|Document[]        $documents
     * @property int|null                                                   $documents_count
     * @property mixed                                                      $charges
     * @property mixed                                                      $customer
     * @property mixed                                                      $detraction
     * @property mixed                                                      $discounts
     * @property mixed                                                      $establishment
     * @property mixed                                                      $guides
     * @property mixed                                                      $identifier
     * @property mixed                                                      $legends
     * @property mixed                                                      $number_full
     * @property mixed                                                      $number_to_letter
     * @property mixed                                                      $perception
     * @property mixed                                                      $prepayments
     * @property mixed                                                      $related
     * @property \Illuminate\Database\Eloquent\Collection|GuideFile[]       $guide_files
     * @property int|null                                                   $guide_files_count
     * @property \Illuminate\Database\Eloquent\Collection|InventoryKardex[] $inventory_kardex
     * @property int|null                                                   $inventory_kardex_count
     * @property \Illuminate\Database\Eloquent\Collection|OrderNoteItem[]   $items
     * @property int|null                                                   $items_count
     * @property PaymentMethodType                                          $payment_method_type
     * @property Person                                                     $person
     * @property \Illuminate\Database\Eloquent\Collection|SaleNote[]        $sale_notes
     * @property int|null                                                   $sale_notes_count
     * @property SoapType                                                   $soap_type
     * @property StateType                                                  $state_type
     * @property User                                                       $user
     * @method static Builder|OrderNote newModelQuery()
     * @method static Builder|OrderNote newQuery()
     * @method static Builder|OrderNote query()
     * @method static Builder|OrderNote whereDefaultState($params)
     * @method static Builder|OrderNote wherePendingState($params)
     * @method static Builder|OrderNote whereProcessedState($params)
     * @method static Builder|OrderNote whereTypeUser()
     * @mixin Eloquent
     * @mixin ModelTenant
     */
    class OrderNote extends ModelTenant
    {
        protected $with = [
            'user',
            'soap_type',
            'state_type',
            'currency_type',
            'items',
        ];

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
            'quotation_id',
            'observation',
            'total_igv_free',

        ];

        protected $casts = [
            'date_of_issue' => 'date',
            'date_of_due' => 'date',
            'delivery_date' => 'date',
            'quotation_id' => 'int',
            'user_id' => 'int',
            'establishment_id' => 'int',
            'customer_id' => 'int',
            'exchange_rate_sale' => 'float',
            'total_prepayment' => 'float',
            'total_charge' => 'float',
            'total_discount' => 'float',
            'total_exportation' => 'float',
            'total_free' => 'float',
            'total_taxed' => 'float',
            'total_unaffected' => 'float',
            'total_exonerated' => 'float',
            'total_igv' => 'float',
            'total_igv_free' => 'float',
            'total_base_isc' => 'float',
            'total_isc' => 'float',
            'total_base_other_taxes' => 'float',
            'total_other_taxes' => 'float',
            'total_taxes' => 'float',
            'total_value' => 'float',
            'total' => 'float',
        ];

        public function getEstablishmentAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setEstablishmentAttribute($value)
        {
            $this->attributes['establishment'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getCustomerAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setCustomerAttribute($value)
        {
            $this->attributes['customer'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getChargesAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setChargesAttribute($value)
        {
            $this->attributes['charges'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getDiscountsAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setDiscountsAttribute($value)
        {
            $this->attributes['discounts'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getPrepaymentsAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setPrepaymentsAttribute($value)
        {
            $this->attributes['prepayments'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getGuidesAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setGuidesAttribute($value)
        {
            $this->attributes['guides'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getRelatedAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setRelatedAttribute($value)
        {
            $this->attributes['related'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getPerceptionAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setPerceptionAttribute($value)
        {
            $this->attributes['perception'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getDetractionAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setDetractionAttribute($value)
        {
            $this->attributes['detraction'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getLegendsAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setLegendsAttribute($value)
        {
            $this->attributes['legends'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getIdentifierAttribute()
        {
            return $this->prefix . '-' . $this->id;
        }

        public function getNumberFullAttribute()
        {
            return $this->prefix . '-' . $this->id;
        }

        /**
         * @return BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        /**
         * @return BelongsTo
         */
        public function soap_type()
        {
            return $this->belongsTo(SoapType::class);
        }

        /**
         * @return BelongsTo
         */
        public function state_type()
        {
            return $this->belongsTo(StateType::class);
        }

        /**
         * @return BelongsTo
         */
        public function person()
        {
            return $this->belongsTo(Person::class, 'customer_id');
        }


        /**
         * @return BelongsTo
         */
        public function currency_type()
        {
            return $this->belongsTo(CurrencyType::class, 'currency_type_id');
        }

        /**
         * @return HasMany
         */
        public function items()
        {
            return $this->hasMany(OrderNoteItem::class);
        }

        /**
         * Devuleve la relacion de los items a traves de order_note_items
         *
         * No implplementado
         *
         * @return BelongsToMany
         */

        public function relation_item()
        {
            return $this->belongsToMany(Item::class, 'order_note_items')
                ->withPivot('id', 'item', 'quantity', 'unit_value', 'affectation_igv_type_id', 'total_base_igv', 'percentage_igv', 'total_igv', 'system_isc_type_id', 'total_base_isc', 'percentage_isc', 'total_isc', 'total_base_other_taxes', 'percentage_other_taxes', 'total_other_taxes', 'total_plastic_bag_taxes', 'total_taxes', 'price_type_id', 'unit_price', 'total_value', 'total_charge', 'total_discount', 'total', 'attributes', 'discounts', 'charges', 'additional_information', 'warehouse_id', 'name_product_pdf');
        }


        /**
         * @return HasMany
         */
        public function documents()
        {
            return $this->hasMany(Document::class);
        }

        /**
         * @return HasMany
         */
        public function sale_notes()
        {
            return $this->hasMany(SaleNote::class);
        }

        /**
         * @return BelongsTo
         */
        public function payment_method_type()
        {
            return $this->belongsTo(PaymentMethodType::class);
        }

        /**
         * @return mixed
         */
        public function getNumberToLetterAttribute()
        {
            $legends = $this->legends;
            $legend = collect($legends)->where('code', '1000')->first();
            return $legend->value;
        }

        /**
         * @param Builder $query
         *
         * @return Builder|null
         */
        public function scopeWhereTypeUser(Builder $query)
        {
            $user = auth()->user();
            return ($user->type == 'seller') ? $query->where('user_id', $user->id) : null;
        }

        public function scopeSearchByDate(Builder $query, $params)
        {
            if ($params['date_start'] !== null && $params['date_end'] !== null) {
                $query->where([['date_of_issue', '>=', $params['date_start']], ['date_of_due', '<=', $params['date_end']]]);
            }

            return $query;
        }

        /**
         * Se usa en la relacion con el inventario kardex en modules/Inventory/Traits/InventoryTrait.php.
         * Tambien se debe tener en cuenta modules/Inventory/Providers/InventoryKardexServiceProvider.php y
         * app/Providers/KardexServiceProvider.php para la correcta gestion de kardex
         *
         * @return MorphMany
         */
        public function inventory_kardex()
        {
            return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
        }


        /**
         * @param Builder $query
         * @param         $params
         *
         * @return Builder
         */
        public function scopeWherePendingState(Builder $query, $params)
        {

            $query
                ->doesntHave('documents')
                ->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']]);
            if ($params['person_id']) {
                $query->where('customer_id', $params['person_id']);
            } else {
                $query->where('user_id', $params['seller_id']);
            }
            return $query;

        }


        /**
         * @param Builder $query
         * @param         $params
         *
         * @return Builder
         */
        public function scopeWhereProcessedState(Builder $query, $params)
        {

            $query
                ->whereHas('documents')
                ->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']]);
            if ($params['person_id']) {
                $query->where('customer_id', $params['person_id']);
            } else {
                $query->where('user_id', $params['seller_id']);
            }
            return $query;

        }


        /**
         * @param Builder $query
         * @param         $params
         *
         * @return Builder
         */
        public function scopeWhereDefaultState(Builder $query, $params)
        {

            $query->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']]);
            if ($params['person_id']) {
                $query->where('customer_id', $params['person_id']);

            } else {
                $query->where('user_id', $params['seller_id']);
            }

            return $query;

        }

        /**
         * Establece el status anulado (11) para el pedido
         * Recorre los items, si estos tienen lotes serán habilitados nuevamente
         *
         * @return $this
         */
        public function VoidOrderNote(): OrderNote
        {
            $order_items = $this->items;
            /** @var OrderNoteItem $item */
            foreach ($order_items as $items) {
                $item = $items->item;
                if (property_exists($item, 'lots')) {
                    $lots = $item->lots;
                    $total_lot = count($lots);
                    for ($i = 0; $i < $total_lot; $i++) {
                        $lot = $lots[$i];
                        if (property_exists($lot, 'has_sale') && $lot->has_sale == true) {
                            $item_lot = ItemLot::find($lot->id);
                            if (!empty($item_lot) && $item_lot->has_sale == true) {
                                $item_lot->setHasSale(false)->push();
                            }
                        }
                    }
                }

                if (isset($item->lots_group)) {
                    if(is_array($item->lots_group) && count($item->lots_group) > 0) {
                            $lots_group = $item->lots_group;
    
                            foreach ($lots_group as $ltg) {
                                $lot = ItemLotsGroup::query()->find($ltg->id);
                                $lot->quantity = $lot->quantity + $ltg->compromise_quantity;
                                $lot->save();
                            }
                    }
                }


            }
            $this->state_type_id = '11';
            return $this;
        }

        /**
         * @return array
         */
        public function getCollectionData()
        {
            $btn_generate = (count($this->documents) > 0 || count($this->sale_notes) > 0) ? false : true;
            $quotation = Quotation::find($this->quotation_id);
            if ($quotation !== null) {
                $quotation = [
                    'id' => $quotation->id,
                    'full_number' => $quotation->getNumberFullAttribute(),
                ];
            } else {
                $quotation = [];
            }
            $dispatches = $this->getDispatches()->transform(function ($row) {
                return $row->getCollectionData();
            });
            $state_type_description = $this->state_type->description;
            if (!empty($dispatches) && count($dispatches) != 0) {
                $state_type_description = 'Despachado';
                // #596
            }
            $miTiendaPe = MiTiendaPe::where('order_note_id',$this->id)->first();
            if(empty($miTiendaPe)){
                $miTiendaPe =[
                    'order_number'=>null,
                ];
            }else{
                $miTiendaPe=[
                'order_number'=>$miTiendaPe->order_number,
                    ];
            }

            return [
                'id' => $this->id,
                'quotation' => (object)$quotation,
                'soap_type_id' => $this->soap_type_id,
                'external_id' => $this->external_id,
                'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
                'date_of_due' => ($this->date_of_due) ? $this->date_of_due->format('Y-m-d') : null,
                'delivery_date' => ($this->delivery_date) ? $this->delivery_date->format('Y-m-d') : null,
                'identifier' => $this->identifier,
                'user_name' => $this->user->name,
                'customer_name' => $this->customer->name,
                'customer_number' => $this->customer->number,
                'currency_type_id' => $this->currency_type_id,
                'total_exportation' => number_format($this->total_exportation, 2),
                // 'total_free' => number_format($this->total_free,2),
                'total_unaffected' => number_format($this->total_unaffected, 2),
                'total_exonerated' => number_format($this->total_exonerated, 2),
                'total_taxed' => number_format($this->total_taxed, 2),
                'total_igv' => number_format($this->total_igv, 2),
                'total' => number_format($this->total, 2),
                'state_type_id' => $this->state_type_id,
                'state_type_description' => $state_type_description,
                'documents' => $this->documents->transform(function ($row) {
                    /** @var Document $row */
                    return [
                        'id' => $row->id,
                        'number_full' => $row->number_full,
                        'state_type_id' => $row->state_type_id,
                        'order_note_id' => $row->order_note_id,
                        'series' => $row->series,
                    ];
                }),
                'sale_notes' => $this->sale_notes,
                'items_details' => $this->items->transform(function ($row) {
                    /** @var Document $row */
                    return [
                        'item_details' => Item::where('id',$row->item_id)->get(),
                        'item' => $row->item,
                        'discounts' => $row->discounts,
                        'quantity' => $row->quantity,
                        'unit_price' => $row->unit_price,
                        'total_discount' => $row->total_discount,

                    ];
                }),
                'btn_generate' => $btn_generate,
                'mi_tienda_pe' => $miTiendaPe,
                'dispatches' => $dispatches,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
                'print_a4' => url('') . "/order-notes/print/{$this->external_id}/a4",
            ];
        }

        /**
         * @return Dispatch[]|Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|Collection|mixed
         */
        public function getDispatches()
        {
            return Dispatch::where('reference_order_note_id', $this->id)->get();
        }

        /**
         * @return int|null
         */
        public function getQuotationId(): ?int
        {
            return $this->quotation_id;
        }

        /**
         * @param int|null $quotation_id
         *
         * @return OrderNote
         */
        public function setQuotationId(?int $quotation_id): OrderNote
        {
            $this->quotation_id = (int) $quotation_id;
            return $this;
        }

        /**
         * @return HasMany
         */
        public function guide_files()
        {
            return $this->hasMany(GuideFile::class);
        }
    }
