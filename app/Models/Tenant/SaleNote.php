<?php

    namespace App\Models\Tenant;

    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Catalogs\DocumentType;
    use App\Traits\SellerIdTrait;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Illuminate\Database\Eloquent\Relations\MorphMany;
    use Illuminate\Database\Query\Builder;
    use Modules\Item\Models\WebPlatform;
    use Modules\Order\Models\OrderNote;
    use Modules\Sale\Models\TechnicalService;
    use Modules\Pos\Models\Tip;

    /**
     * Class SaleNote
     *
     * @package App\Models\Tenant\
     * @mixin ModelTenant
     * @property CurrencyType                                   $currency_type
     * @property Collection|Document[]                          $documents
     * @property int|null                                       $documents_count
     * @property Establishment                                  $establishment
     * @property mixed                                          $charges
     * @property mixed                                          $customer
     * @property mixed                                          $detraction
     * @property mixed                                          $discounts
     * @property mixed                                          $guides
     * @property mixed                                          $identifier
     * @property mixed                                          $legends
     * @property string                                         $number_full
     * @property mixed                                          $number_to_letter
     * @property mixed                                          $perception
     * @property mixed                                          $prepayments
     * @property mixed                                          $related
     * @property Collection|InventoryKardex[]                   $inventory_kardex
     * @property int|null                                       $inventory_kardex_count
     * @property Collection|SaleNoteItem[]                      $items
     * @property int|null                                       $items_count
     * @property Collection|Kardex[]                            $kardex
     * @property int|null                                       $kardex_count
     * @property PaymentMethodType                              $payment_method_type
     * @property Collection|SaleNotePayment[]                   $payments
     * @property int|null                                       $payments_count
     * @property Person                                         $person
     * @property Quotation                                      $quotation
     * @property SoapType                                       $soap_type
     * @property StateType                                      $state_type
     * @property User                                           $user
     * @property User                                           $seller
     * @property int                                            $id
     * @property string|null                    $grade
     * @property string|null                    $section
     * @property int                                            $user_id
     * @property string                                         $external_id
     * @property int                                            $establishment_id
     * @property string                                         $soap_type_id
     * @property string                                         $state_type_id
     * @property string                                         $prefix
     * @property string|null                                    $series
     * @property int|null                                       $number
     * @property Carbon                                         $date_of_issue
     * @property Carbon                                         $time_of_issue
     * @property int                                            $customer_id
     * @property string                                         $currency_type_id
     * @property string|null                                    $payment_method_type_id
     * @property float                                          $exchange_rate_sale
     * @property bool                                           $apply_concurrency
     * @property bool                                           $enabled_concurrency
     * @property Carbon|null                                    $automatic_date_of_issue
     * @property int|null                                       $quantity_period
     * @property string|null                                    $type_period
     * @property float                                          $total_prepayment
     * @property float                                          $total_charge
     * @property float                                          $total_discount
     * @property float                                          $total_exportation
     * @property float                                          $total_free
     * @property float                                          $total_taxed
     * @property float                                          $total_unaffected
     * @property float                                          $total_exonerated
     * @property float                                          $total_igv
     * @property float                                          $total_base_isc
     * @property float                                          $total_isc
     * @property float                                          $total_base_other_taxes
     * @property float                                          $total_other_taxes
     * @property float                                          $total_plastic_bag_taxes
     * @property float                                          $total_taxes
     * @property float                                          $total_value
     * @property float                                          $total
     * @property string|null                                    $additional_information
     * @property string|null                                    $filename
     * @property int|null                                       $quotation_id
     * @property int|null                                       $order_note_id
     * @property int|null                                       $technical_service_id
     * @property int|null                                       $order_id
     * @property bool                                           $total_canceled
     * @property bool                                           $changed
     * @property bool                                           $paid
     * @property string|null                                    $license_plate
     * @property string|null                                    $plate_number
     * @property string|null                                    $reference_data
     * @property string|null                                    $observation
     * @property string|null                                    $purchase_order
     * @property int|null                                       $document_id
     * @property int|null                                       $user_rel_suscription_plan_id
     * @property Carbon|null                                    $due_date
     * @property Carbon|null                                    $created_at
     * @property Carbon|null                                    $updated_at
     * @property int|null                                       $seller_id
     * @property Order|null                                     $order
     * @property OrderNote|null                                 $order_note
     * @property TechnicalService|null                          $technical_service
     * @property Collection|CashDocument[]                      $cash_documents
     * @property Collection|Kardex[]                            $kardexes
     * @property Collection|SaleNotePayment[]                   $sale_note_payments
     * @method static \Illuminate\Database\Eloquent\Builder|SaleNote newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SaleNote newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SaleNote query()
     * @method static \Illuminate\Database\Eloquent\Builder|SaleNote whereNotChanged()
     * @method static \Illuminate\Database\Eloquent\Builder|SaleNote whereStateTypeAccepted()
     * @method static \Illuminate\Database\Eloquent\Builder|SaleNote whereTypeUser()
     * @method static \Illuminate\Database\Eloquent\Builder|SaleNote WhereEstablishmentId()
     * @property-read int|null                                  $cash_documents_count
     * @property-read Collection|\App\Models\Tenant\GuideFile[] $guide_files
     * @property-read int|null                                  $guide_files_count
     * @property-read int|null                                  $kardexes_count
     * @property-read int|null                                  $sale_note_payments_count
     * @method static \Illuminate\Database\Eloquent\Builder|SaleNote whereEstablishmentId($establishment_id = 0)
     */
    class SaleNote extends ModelTenant
    {
        use UsesTenantConnection;
        use SellerIdTrait;

        protected $with = [
            'user',
            'soap_type',
            'state_type',
            'currency_type',
            'items',
            'payments'
        ];

        protected $fillable = [
            'user_id',
            'external_id',
            'establishment_id',
            'establishment',
            'soap_type_id',
            'state_type_id',
            'grade',
            'section',
            'prefix',

            'date_of_issue',
            'time_of_issue',
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
            'total_canceled',
            'quotation_id',
            'order_note_id',
            'apply_concurrency',
            'type_period',
            'quantity_period',
            'automatic_date_of_issue',
            'enabled_concurrency',
            'series',
            'number',
            'paid',
            'payment_method_type_id',
            'license_plate',
            'observation',
            'reference_data',
            'plate_number',
            'purchase_order',
            'due_date',
            'total_plastic_bag_taxes',
            'additional_information',
            'document_id',
            'seller_id',
            'order_id',
            'technical_service_id',
            // 'changed',
            'user_rel_suscription_plan_id',
            'subtotal',
            'total_igv_free',
            'unique_filename', //registra nombre de archivo unico (campo para evitar duplicidad)
        ];

        protected $casts = [
            'user_id' => 'int',
            'establishment_id' => 'int',
            'number' => 'int',
            'customer_id' => 'int',
            'exchange_rate_sale' => 'float',
            'apply_concurrency' => 'bool',
            'enabled_concurrency' => 'bool',
            'quantity_period' => 'int',
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
            'total_plastic_bag_taxes' => 'float',
            'total_taxes' => 'float',
            'total_value' => 'float',
            'total' => 'float',
            'quotation_id' => 'int',
            'order_note_id' => 'int',
            'technical_service_id' => 'int',
            'order_id' => 'int',
            'total_canceled' => 'bool',
            // 'changed' => 'bool',
            'paid' => 'bool',
            'document_id' => 'int',
            'user_rel_suscription_plan_id' => 'int',
            'seller_id' => 'int',
            'date_of_issue' => 'date',
            'automatic_date_of_issue' => 'date',
            'due_date' => 'date',
        ];

        public static function boot()
        {
            parent::boot();
            static::creating(function (self $model) {
                self::adjustSellerIdField($model);
            });

        }

        /**
         * Busca el ultimo numero basado en series y el prefijo.
         *
         * @param SaleNote $model
         *
         * @return int
         */
        public static function getLastNumberByModel(SaleNote $model)
        {
            $sn = SaleNote::where(
                [
                    'series' => $model->series,
                    'prefix' => $model->prefix,
                    // 'number',
                ])
                ->select('number')
                ->orderBy('number', 'desc')
                ->first();
            $return = 0;
            if ( !empty($sn)) {
                $return += $sn->number;
            }
            return $return + 1;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function customer()
        {
            return $this->belongsTo(Person::class, 'customer_id');
        }

        /**
         * Obtiene la fecha de vencimiento
         *
         * @return mixed
         */
        public function getDueDate()
        {
            return $this->due_date;
        }

        /**
         * Establece la fecha de vencimiento
         *
         * @param mixed $due_date
         *
         * @return SaleNote
         */
        public function setDueDate($due_date)
        {
            $this->due_date = $due_date;
            return $this;
        }

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
        public function seller()
        {
            return $this->belongsTo(User::class,'seller_id');
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
        public function establishment()
        {
            return $this->belongsTo(Establishment::class);
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
            return $this->hasMany(SaleNoteItem::class);
        }

        /**
         * @return HasMany
         */
        public function kardex()
        {
            return $this->hasMany(Kardex::class);
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
         * @return HasMany
         */
        public function documents()
        {
            return $this->hasMany(Document::class);
        }

        /**
         * @return BelongsTo
         * order from ecommerce
         */
        public function order()
        {
            return $this->belongsTo(Order::class);
        }

        /**
         * @return BelongsTo
         */
        public function technical_service()
        {
            return $this->belongsTo(TechnicalService::class);
        }
        
        /**
         * @return BelongsTo
         */
        public function relation_establishment()
        {
            return $this->belongsTo(Establishment::class, 'establishment_id');
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
         * @return string
         */
        public function getNumberFullAttribute()
        {
            $number_full = ($this->series && $this->number) ? $this->series . '-' . $this->number : $this->prefix . '-' . $this->id;

            return $number_full;
        }


        /**
         * @param $query
         *
         * @return null
         */
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


        /**
         * @param $query
         *
         * @return mixed
         */
        public function scopeWhereStateTypeAccepted($query)
        {
            return $query->whereIn('state_type_id', ['01', '03', '05', '07', '13']);
        }

        /**
         * @param $query
         *
         * @return mixed
         */
        public function scopeWhereNotChanged($query)
        {
            return $query->where('changed', false);
        }

        /**
         * @return BelongsTo
         */
        public function quotation()
        {
            return $this->belongsTo(Quotation::class);
        }

        /**
         * @return BelongsTo
         */
        public function payment_method_type()
        {
            return $this->belongsTo(PaymentMethodType::class);
        }

        /**
         * @param Configuration|null $configuration
         *
         * @return array
         */
        public function getCollectionData(Configuration $configuration = null)
        {
            if ($configuration == null) {
                $configuration = Configuration::first();
            }
            $total_paid = number_format($this->payments->sum('payment'), 2, '.', '');
            $total_pending_paid = number_format($this->total - $total_paid, 2, '.', '');
            $document_id = $this->document_id;
            // Normalmente, un documento tendrá el id de la NV,
            // cuando se hace un CPE a partir de varias NV,
            // se guarda el id del documento en el NV
            /** @var Collection $documents */
            $documents = $this->documents;
            if ( !empty($document_id) && $documents->count() < 1) {
                $documents = Document::where('id', $document_id)->get();
            }
            $total_documents = $documents->count();

            $btn_generate = ($total_documents > 0) ? false : true;
            $btn_payments = ($total_documents > 0) ? false : true;
            $due_date = ( !empty($this->due_date)) ? $this->due_date->format('Y-m-d') : null;

            if(emptY($this->seller_id)) {
                $this->seller_id = $this->user_id;
            }
            $this->payments = $this->getTransformPayments();
            $message_text = '';
            if ( !empty($this->number_full) && !empty($this->external_id)) {
                $message_text = "Su comprobante de nota de venta {$this->number_full} ha sido generado correctamente, puede revisarlo en el siguiente enlace: " .
                    url('') . "/sale-notes/print/{$this->external_id}/a4" . '';
            }
            $canSentToOtherServer = false;
            if ($configuration->isSendDataToOtherServer() == true && auth()->user()->type === 'admin') {
                $alreadySent = SaleNoteMigration::where([
                    'sale_notes_id' => $this->id,
                    'success' => true
                ])->first();
                if ($alreadySent == false) {
                    $canSentToOtherServer = true;
                }
            }
            $web_platforms = $this->getPlatformThroughItems();
            $child_name = '';
            $child_number = '';
            $customer = $this->customer;
            if(property_exists($customer,'children')){
                $child = $customer->children;
                $child_name= $child->name;
                $child_number= $child->number;
            }
            $person = $this->person;
            $mails = $person->getCollectionData();
            $customer_email=  $mails['optional_email_send'];

            return [
                'id' => $this->id,
                'soap_type_id' => $this->soap_type_id,
                'external_id' => $this->external_id,
                'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
                'time_of_issue' => $this->time_of_issue,
                'identifier' => $this->identifier,
                'full_number' => $this->series . '-' . $this->number,
                'customer_name' => $customer->name,
                'customer_number' => $customer->number,
                'children_name' => $child_name,
                'children_number' => $child_number,
                'currency_type_id' => $this->currency_type_id,
                'total_exportation' => self::FormatNumber($this->total_exportation),
                'total_free' => self::FormatNumber($this->total_free),
                'total_unaffected' => self::FormatNumber($this->total_unaffected),
                'total_exonerated' => self::FormatNumber($this->total_exonerated),
                'total_taxed' => self::FormatNumber($this->total_taxed),
                'total_igv' => self::FormatNumber($this->total_igv),
                'total' => self::FormatNumber($this->total),
                'state_type_id' => $this->state_type_id,
                'state_type_description' => $this->state_type->description,
                'document_id' => $this->document_id,
                'documents' => $documents->transform(function ($row) {
                    /** @var Document $row */
                    return [
                        'id' => $row->id,
                        'number_full' => $row->number_full,
                    ];
                }),
                'btn_generate' => $btn_generate,
                'btn_payments' => $btn_payments,
                'changed' => (boolean)$this->changed,
                'enabled_concurrency' => (boolean)$this->enabled_concurrency,
                'quantity_period' => $this->quantity_period,
                'type_period' => $this->type_period,
                'apply_concurrency' => (boolean)$this->apply_concurrency,
                'created_at' => $this->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
                'paid' => (bool)$this->paid,
                'total_canceled' => (bool)$this->total_canceled,
                'license_plate' => $this->license_plate,
                'total_paid' => $total_paid,
                'total_pending_paid' => $total_pending_paid,
                'user_name' => ($this->user) ? $this->user->name : '',
                'quotation_number_full' => ($this->quotation) ? $this->quotation->number_full : '',
                'sale_opportunity_number_full' => isset($this->quotation->sale_opportunity)
                    ? $this->quotation->sale_opportunity->number_full : '',
                'number_full' => $this->number_full,
                'print_a4' => url('') . "/sale-notes/print/{$this->external_id}/a4",
                'print_ticket' => url('') . "/sale-notes/print/{$this->external_id}/ticket",
                'print_a5' => url('') . "/sale-notes/print/{$this->external_id}/a5",
                'print_ticket_58' => url('') . "/sale-notes/print/{$this->external_id}/ticket_58",
                'purchase_order' => $this->purchase_order,
                'due_date' => $due_date,
                'sale_note' => $this,
                'seller_id' => $this->seller_id,
                'message_text' => $message_text,
                'serie' => $this->series,
                'number' => $this->number,
                // 'number' => $this->number_full,
                'grade' => $this->getGrade(),
                'section' => $this->getSection(),
                'send_other_server' => $canSentToOtherServer,
                'web_platforms' => $web_platforms,
                'customer_email' => $customer_email,
                'customer_telephone' => optional($this->person)->telephone,
                'seller' => $this->seller,
                'seller_name'                     => ((int)$this->seller_id !=0)?$this->seller->name:'',
// 'number' => $this->number,
            ];
        }

        /**
         * @return Collection
         */
        public function getTransformPayments()
        {

            $payments = $this->payments()->get();
            return $payments->transform(function ($row, $key) {
                /** @var SaleNotePayment $row */
                return [
                    'id' => $row->id,
                    'sale_note_id' => $row->sale_note_id,
                    'date_of_payment' => $row->date_of_payment->format('Y-m-d'),
                    'payment_method_type_id' => $row->payment_method_type_id,
                    'has_card' => $row->has_card,
                    'card_brand_id' => $row->card_brand_id,
                    'reference' => $row->reference,
                    'payment' => $row->payment,
                    'payment_method_type' => $row->payment_method_type,
                    'payment_destination_id' => ($row->global_payment) ? ($row->global_payment->type_record == 'cash' ? 'cash' : $row->global_payment->destination_id) : null,
                    'payment_filename' => ($row->payment_file) ? $row->payment_file->filename : null,
                ];
            });
        }

        /**
         * @return HasMany
         */
        public function payments()
        {
            return $this->hasMany(SaleNotePayment::class);
        }

        /**
         * Devuelve una coleccion de plataformas web basado en los items.
         *
         * @return \Illuminate\Database\Eloquent\Builder[]|Collection|Builder[]|\Illuminate\Support\Collection|mixed|WebPlatform|WebPlatform[]
         */
        public function getPlatformThroughItems()
        {

            /**
             * @var Collection  $items
             * @var WebPlatform $web_platforms
             */
            $items = $this->items->pluck('item_id');
            $web_platform_table_name = (new WebPlatform())->getTable();
            $item_table_name = (new Item())->getTable();
            return WebPlatform::leftJoin('items', "$web_platform_table_name.id", '=', "$item_table_name.web_platform_id")
                ->select("$web_platform_table_name.id", "$web_platform_table_name.name")
                ->wherein("$item_table_name.id", $items)
                ->get();
        }
        /**
         * Devuelve el vendedor asociado, Si seller id es nulo, devolverá el usuario del campo user.
         *
         * @return User
         */
        public function getSellerData()
        {
            if ( !empty($this->seller_id)) {
                return $this->seller;
            }
            return $this->user;

        }
        public static function FormatNumber($number, $decimal = 2)
        {
            return number_format($number, $decimal);
        }

        /**
         * Genera la estructura necesaria para exportar por api
         *
         * @return array
         */
        public function getDataToApiExport()
        {

            $date_of_issue = ($this->date_of_issue) ? $this->date_of_issue->format('Y-m-d') : '';
            $sale_note_items = SaleNoteItem::where('sale_note_id', $this->id)->get();
            $items = [];
            foreach ($sale_note_items as $item) {
                /** @var SaleNoteItem $item */
                $tem_item = [];
                $tem_item['id'] = $item->id;
                $tem_item['currency_type_id'] = $item->currency_type_id;
                $tem_item['quantity'] = $item->quantity;
                $tem_item['unit_value'] = $item->unit_value;
                $tem_item['affectation_igv_type_id'] = $item->affectation_igv_type_id;
                $tem_item['total_base_igv'] = $item->total_base_igv;
                $tem_item['percentage_igv'] = $item->percentage_igv;
                $tem_item['total_igv'] = $item->total_igv;
                $tem_item['system_isc_type_id'] = $item->system_isc_type_id;
                $tem_item['total_base_isc'] = $item->total_base_isc;
                $tem_item['percentage_isc'] = $item->percentage_isc;
                $tem_item['total_isc'] = $item->total_isc;
                $tem_item['total_base_other_taxes'] = $item->total_base_other_taxes;
                $tem_item['percentage_other_taxes'] = $item->percentage_other_taxes;
                $tem_item['total_other_taxes'] = $item->total_other_taxes;
                $tem_item['total_plastic_bag_taxes'] = $item->total_plastic_bag_taxes;
                $tem_item['total_taxes'] = $item->total_taxes;
                $tem_item['price_type_id'] = $item->price_type_id;
                $tem_item['unit_price'] = $item->unit_price;
                $tem_item['total_value'] = $item->total_value;
                $tem_item['total_discount'] = $item->total_discount;
                $tem_item['total_charge'] = $item->total_charge;
                $tem_item['total'] = $item->total;
                $tem_item['attributes'] = $item->attributes;
                $tem_item['charges'] = $item->charges;
                $tem_item['discounts'] = $item->discounts;
                $tem_item['affectation_igv_type'] = $item->affectation_igv_type;
                $it = $item->item;
                $ot = Item::find($it->id);
                $item_select = Item::where('id', $it->id)->select(
                    'name',
                    'second_name',
                    'description',
                    'model',
                    'technical_specifications',
                    'item_type_id',
                    'item_code',
                    'date_of_due',
                    'account_id',
                    'item_code_gs1',
                    'unit_type_id',
                    'currency_type_id',
                    'sale_unit_price',
                    'purchase_has_igv',
                    'has_igv',
                    'amount_plastic_bag_taxes',
                    'sale_affectation_igv_type_id',
                    'purchase_affectation_igv_type_id',
                    'calculate_quantity',
                    'is_set',
                    'has_plastic_bag_taxes',
                    'lot_code',
                    'lots_enabled',
                    'series_enabled',
                    'attributes',
                    'web_platform_id',
                    'warehouse_id',
                    'status',
                    'cod_digemid',
                    'sanitary'
                )->first();
                $tem_item['full_item'] = $item_select !== null ? $item_select->toArray() : [];
                $property = [
                    'full_description',
                    'name',
                    'description',
                    'currency_type_id',
                    'internal_id',
                    'item_code',
                    'currency_type_symbol',
                    'sale_unit_price',
                    'purchase_unit_price',
                    'unit_type_id',
                    'sale_affectation_igv_type_id',
                    'purchase_affectation_igv_type_id',
                    'calculate_quantity',
                    'has_igv',
                    'is_set',
                    'aux_quantity',
                    'brand',
                    'category',
                    'stock',
                    'image',
                    'warehouses',
                    'unit_price',
                    'presentation',
                ];
                $t_it = [
                    'id' => property_exists($it, 'id') ? $it->id : $ot->id,
                    'item_id' => property_exists($it, 'id') ? $it->id : $ot->id,

                ];
                for ($i = 0; $i < count($property); $i++) {
                    $w = $property[$i];
                    $t_it[$w] = property_exists($it, $w) ? $it->{$w} : $ot->{$w};
                }
                $tem_item['item'] = $t_it;
                $items[] = $tem_item;
            }
            $payments_model = SaleNotePayment::where('sale_note_id', $this->id)->get();
            $payments = [];

            foreach ($payments_model as $payment) {
                /** @var SaleNotePayment $payment */
                $payments[] = $payment->toArray();
            }
            $attributes = $this->attributes;

            $customer = Person::find($this->customer_id);
            if (empty($customer->identity_document_type_id)) $customer->identity_document_type_id = 6;
            if (empty($customer->country_id)) $customer->country_id = 'PE';
            if (empty($customer->district_id)) $customer->district_id = '';
            $customer->codigo_tipo_documento_identidad = $customer->identity_document_type_id;
            $customer->numero_documento = $customer->number;
            $customer->apellidos_y_nombres_o_razon_social = $customer->name;
            $customer->codigo_pais = $customer->country_id;
            $customer->ubigeo = $customer->district_id;
            $customer->direccion = $customer->address;
            $customer->correo_electronico = $customer->email;
            $customer->telefono = $customer->telephone;
            $datos_del_cliente_o_receptor = $customer->toArray();
            $empty_ob = (object)[];
            $data = [

                'prefix' => $this->prefix,
                'series_id' => 10,
                'establishment_id' => null,
                'date_of_issue' => $date_of_issue,
                'time_of_issue' => $this->time_of_issue,
                'customer_id' => $this->customer_id,
                'currency_type_id' => $this->currency_type_id,
                'purchase_order' => $this->purchase_order,
                'exchange_rate_sale' => $this->exchange_rate_sale,
                'total_prepayment' => $this->total_prepayment,
                'total_charge' => $this->total_charge,
                'total_discount' => $this->total_discount,
                'total_free' => $this->total_free,
                'total_exportation' => $this->total_exportation,
                'total_taxed' => $this->total_taxed,
                'total_unaffected' => $this->total_unaffected,
                'total_exonerated' => $this->total_exonerated,
                'total_igv' => $this->total_igv,
                'total_base_isc' => $this->total_base_isc,
                'total_isc' => $this->total_isc,
                'total_base_other_taxes' => $this->total_base_other_taxes,
                'total_other_taxes' => $this->total_other_taxes,
                'total_taxes' => $this->total_taxes,
                'total_value' => $this->total_value,
                'total' => $this->total,
                'operation_type_id' => $this->operation_type_id,
                'items' => $items,
                'force_create_if_not_exist' => true,

                'charges' => $this->charge,
                'attributes' => $attributes,
                'guides' => null,
                // 'guides'                 => isset($attributes['guides']) ? $attributes['guides'] : $empty_ob,
                'discounts' => isset($attributes['discounts']) ? $attributes['discounts'] : $empty_ob,
                'payments' => $payments,
                'additional_information' => $this->additional_information,

                'actions' => [],
                'apply_concurrency' => (bool)$this->apply_concurrency,
                'type_period' => $this->type_period,
                'quantity_period' => $this->quantity_period,
                'automatic_date_of_issue' => $this->automatic_date_of_issue,
                'enabled_concurrency' => $this->enabled_concurrency,
                'datos_del_cliente_o_receptor' => $datos_del_cliente_o_receptor,

            ];

            $data['quantity_period'] = (int)$data['quantity_period'];
            $data['apply_concurrency'] = (bool)$data['apply_concurrency'];
            $data['enabled_concurrency'] = (bool)$data['enabled_concurrency'];

            return $data;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function guide_files()
        {
            return $this->hasMany(GuideFile::class);
        }

        /**
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @param int                                   $establishment_id
         *
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function scopeWhereEstablishmentId(\Illuminate\Database\Eloquent\Builder $query, $establishment_id = 0)
        {

            if ($establishment_id != 0) {
                $query->where('establishment_id', $establishment_id);
            }
            return $query;
        }


        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function order_note()
        {
            return $this->belongsTo(OrderNote::class);
        }


        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function cash_documents()
        {
            return $this->hasMany(CashDocument::class);
        }


        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function kardexes()
        {
            return $this->hasMany(Kardex::class);
        }


        /**
         * @return \Illuminate\Database\Eloquent\Relations\HasMany
         */
        public function sale_note_payments()
        {
            return $this->hasMany(SaleNotePayment::class);
        }

        public function tip()
        {
            return $this->morphOne(Tip::class, 'origin');
        }

        /**
         *
         * Filtros para reportes de comisiones
         * Usado en:
         * Modules\Report\Http\Controllers\ReportCommissionController
         *
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @param $date_start
         * @param $date_end
         * @param $establishment_id
         * @param $user_type
         * @param $user_seller_id
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function scopeWhereFilterCommission($query, $date_start, $date_end, $establishment_id, $user_type, $user_seller_id, $row_user_id){

            $query->whereStateTypeAccepted()
                    ->whereBetween('date_of_issue', [$date_start, $date_end])
                    ->whereEstablishmentId($establishment_id);

            if($user_seller_id){
                $query->where($user_type, $user_seller_id);
            }else{
                $query->where($user_type, $row_user_id);
            }

            return $query;
        }

        /**
         * @return string|null
         */
        public function getGrade(): ?string
        {
            return $this->grade;
        }

        /**
         * @param string|null $grade
         *
         * @return SaleNote
         */
        public function setGrade(?string $grade): SaleNote
        {
            $this->grade = $grade;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getSection(): ?string
        {
            return $this->section;
        }

        /**
         * @param string|null $section
         *
         * @return SaleNote
         */
        public function setSection(?string $section): SaleNote
        {
            $this->section = $section;
            return $this;
        }

        /**
         * Devuelve el modelo del tipo de documetno actual
         *
         * @return DocumentType
         */
        public function getDocumentType(){
            return DocumentType::find('80');
        }


        /**
         *
         * Filtros para reporte utilidades
         * Usado en:
         * DashboardUtility - Obtener total descuentos globales
         *
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @param $establishment_id
         * @param $d_start
         * @param $d_end
         * @param $item_id
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function scopeWhereFilterDashboardUtility($query, $establishment_id, $d_start, $d_end, $item_id)
        {

            $query->where([['establishment_id', $establishment_id], ['changed', false]])->whereStateTypeAccepted();

            if($d_start && $d_end) $query->whereBetween('date_of_issue', [$d_start, $d_end]);

            if($item_id)
            {
                $query->whereHas('items', function($q) use($item_id){
                    $q->where('item_id', $item_id);
                });
            }

            return $query;
        }

        
        /**
         *
         * Obtener notas de venta filtradas por el id de los items (SaleNoteItem)
         * 
         * Usado en:
         * DashboardUtility - Obtener totales
         *
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @param array $sale_note_ids
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function scopeWhereRecordsByItems($query, $sale_note_ids)
        {
            return$query->withOut(['user', 'soap_type', 'state_type', 'currency_type', 'items', 'payments'])
                        ->whereIn('id', $sale_note_ids)
                        ->select('id', 'total', 'currency_type_id', 'exchange_rate_sale');

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
         * Filtro para no incluir relaciones en consulta
         *
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @return \Illuminate\Database\Eloquent\Builder
         */  
        public function scopeWhereFilterWithOutRelations($query)
        {
            return $query->withOut([
                'user',
                'soap_type',
                'state_type',
                'currency_type',
                'items',
                'payments'
            ]);
        }


        /**
         * 
         * Obtener vuelto para mostrar en pdf
         *
         * @return float
         */
        public function getChangePayment()
        {
            return ($this->total - $this->payments->sum('payment')) - $this->payments->sum('change');
        }

        /**
         * 
         * Obtener porcentaje de cargos para mostrar en pdf
         *
         * @return float
         */
        public function getTotalFactor()
        {
            $total_factor = 0;

            if($this->charges)
            {
                $total_factor = collect($this->charges)->sum('factor') * 100;
            }

            return $total_factor;
        }

        
        /**
         * 
         * Filtrar por rango de fechas
         * 
         * @param \Illuminate\Database\Eloquent\Builder $query
         * @return \Illuminate\Database\Eloquent\Builder
         * 
         */
        public function scopeFilterRangeDateOfIssue($query, $date_start, $date_end)
        {
            return $query->whereBetween('date_of_issue', [$date_start, $date_end]);
        }

        
        /**
         * 
         * Obtener la fecha de vencimiento y aplicar formato
         *
         * @return string
         */
        public function getFormatDueDate()
        {
            return $this->due_date ? $this->generalFormatDate($this->due_date) : null;
        }


        /**
         * 
         * Obtener descripción del tipo de documento
         *
         * @return string
         */
        public function getDocumentTypeDescription()
        {
            return 'NOTA DE VENTA';
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
