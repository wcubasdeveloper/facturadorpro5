<?php

    /**
     */

    namespace Modules\Suscription\Models\Tenant;


    use App\Http\Controllers\Tenant\SaleNoteController;
    use App\Http\Requests\Tenant\SaleNoteRequest;
    use App\Models\Tenant\Document;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\ExchangeRate;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\SaleNote;
    use App\Models\Tenant\Series;
    use App\Models\Tenant\User;
    use Auth;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * Class UserRelSuscriptionPlan
     *
     * @property int                            $id
     * @property int|null                       $user_id
     * @property int|null                       $suscription_plan_id
     * @property int|null                       $cat_period_id
     * @property string                         $items
     * @property bool                           $editable
     * @property bool                           $deletable
     * @property string|null                    $start_date
     * @property string|null                    $grade
     * @property string|null                    $section
     * @property Carbon|null                    $created_at
     * @property Carbon|null                    $updated_at
     * @property CatPeriod|null                 $cat_period
     * @property SuscriptionPlan|null           $suscription_plan
     * @property User|null                      $user
     * @property int|null                       $quantity_period
     * @property int|null                       $children_customer_id
     * @property \App\Models\Tenant\Person|null $children_customer_relation
     * @property int|null                       $parent_customer_id
     * @property \App\Models\Tenant\Person|null $parent_customer_relation
     * @property int|null                       $customer_id
     * @property \App\Models\Tenant\Person|null $customer_relation
     * @property Carbon|null                    $automatic_date_of_issue
     * @property string|null                    $dates_of_documents
     * @property string|null                    $documents
     * @property string|null                    $sale_notes
     * @property string                         $customer
     * @property string                         $parent_customer
     * @property string                         $children_customer
     * @property bool                           $apply_concurrency
     * @property bool                           $enabled_concurrency
     * @property float|null                     $total
     * @property string|null                    $currency_type_id
     * @property string|null                    $payment_method_type_id
     * @property float|null                     $exchange_rate_sale
     * @property float|null                     $total_prepayment
     * @property float|null                     $total_charge
     * @property float|null                     $total_discount
     * @property float|null                     $total_exportation
     * @property float|null                     $total_free
     * @property float|null                     $total_taxed
     * @property float|null                     $total_unaffected
     * @property float|null                     $total_exonerated
     * @property float|null                     $total_igv
     * @property float|null                     $total_igv_free
     * @property float|null                     $total_base_isc
     * @property float|null                     $total_isc
     * @property float|null                     $total_base_other_taxes
     * @property float|null                     $total_other_taxes
     * @property float|null                     $total_taxes
     * @property float|null                     $total_value
     * @property string|null                    $charges
     * @property string|null                    $attributes
     * @property string|null                    $discounts
     * @property string|null                    $prepayments
     * @property string|null                    $related
     * @property string|null                    $perception
     * @property string|null                    $detraction
     * @property string|null                    $legends
     * @property string|null                    $terms_condition
     * @package App\Models\Tenant\ModelTenant
     * @method static Builder|UserRelSuscriptionPlan newModelQuery()
     * @method static Builder|UserRelSuscriptionPlan newQuery()
     * @method static Builder|UserRelSuscriptionPlan query()
     * @mixin \Eloquent
     *
     *
     *
     */
    class UserRelSuscriptionPlan extends ModelTenant
    {
        use UsesTenantConnection;

        protected $casts = [
            'user_id' => 'int',
            'suscription_plan_id' => 'int',
            'cat_period_id' => 'int',
            'editable' => 'bool',
            'deletable' => 'bool',
            'total' => 'float',
            'quantity_period' => 'int',
            'children_customer_id' => 'int',
            'parent_customer_id' => 'int',
            'customer_id' => 'int',
            'apply_concurrency' => 'bool',
            'enabled_concurrency' => 'bool',
            'start_date' => 'date',
            'automatic_date_of_issue' => 'date',
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
            'total_value' => 'float'
        ];


        protected $fillable = [
            'user_id',
            'suscription_plan_id',
            'cat_period_id',
            'items',
            'editable',
            'deletable',
            'start_date',
            'quantity_period',
            'children_customer_id',
            'parent_customer_id',
            'customer_id',
            'automatic_date_of_issue',
            'dates_of_documents',
            'documents',
            'sale_notes',
            'customer',
            'parent_customer',
            'children_customer',
            'apply_concurrency',
            'enabled_concurrency',
            'grade',
            'section',
            'total',
            'currency_type_id',
            'payment_method_type_id',
            'exchange_rate_sale',
            'total_prepayment',
            'total_charge',
            'total_discount',
            'total_exportation',
            'total_free',
            'total_taxed',
            'total_unaffected',
            'total_exonerated',
            'total_igv',
            'total_igv_free',
            'total_base_isc',
            'total_isc',
            'total_base_other_taxes',
            'total_other_taxes',
            'total_taxes',
            'total_value',
            'charges',
            'attributes',
            'discounts',
            'prepayments',
            'related',
            'perception',
            'detraction',
            'legends',
            'terms_condition'
        ];

        public static function boot()
        {
            parent::boot();
            static::saving(function (self $item) {

            });
        }

        /**
         * obtiene la fecha en ingles y la devuelve en español
         * @param string $date
         *
         * @return string
         */
        protected static  function  setSpanishDate($date = ''){
            $dates = [
                'january'=>'Enero',
                'february'=>'Febrero',
                'march'=>'Marzo',
                'april'=>'Abril',
                'may'=>'Mayo',
                'june'=>'Junio',
                'july'=>'Julio',
                'august'=>'Agosto',
                'september'=>'Septiembre',
                'october'=>'Octubre',
                'november'=>'Noviembre',
                'december'=>'Diciembre',
            ];
            $date = strtolower($date);
            return $dates[$date] ?? ucfirst($date);
        }
        public static function setSaleNote(self $plan)
        {
            $qtyPeriods = (int)$plan->getQuantityPeriod();
            if ($qtyPeriods < 1) return null;
            $notas = $plan->sale_notes;
            // Solo se toma en cuenta cuando no tiene notas de venta.
            // if (strlen($notas) > 2) return null;

            $typerPeriod = $plan->getCatPeriod();

            $parent = $plan->parent_customer;
            $child = $plan->children_customer;
            $parent->child = $child;
            $customer = $parent;

            Carbon::setLocale('es');
            Carbon::setLocale('Spanish_Peru');
            setlocale(LC_ALL, 'Spanish_Peru');

            /** @var \Illuminate\Support\Carbon $start_date */
            $start_date = $plan->start_date;

            $user = Auth::user();
            $stablisment = Establishment::where('id', '!=', 0)->first();
            if ( !empty($user)) {
                $stablisment = $user->establishment;

            }

            $serie = Series::where([
                'establishment_id' => $stablisment->id,
                'document_type_id' => '80',
            ])->first();
            $ids = [];
            $time_of_issue = Carbon::now()->format('H:m:s');
            for ($i = 0; $i < $qtyPeriods; $i++) {
                $date_of_issue = $start_date;
                $date = $start_date;
                $due_date = $date;
                $payments = [];
                $items = json_decode(json_encode($plan->items), true);
                $item = [];
                foreach ($items as $it) {
                    $time = self::setSpanishDate($date->formatLocalized('%B'));
                    if (isset($it['name_product_pdf'])) {
                        if ( !empty($item['name_product_pdf'])) {
                            $it['name_product_pdf'] .= " - $time";
                        } else {
                            $it['name_product_pdf'] = $it['item']['description'] . " - $time";
                        }
                    } else {
                        $it['name_product_pdf'] = $it['item']['description'] . " - $time";

                    }
                    $it['item']['name_product_pdf'] = $it['name_product_pdf'];
                    $item[] = $it;

                }
                $iut = $item;
                $data = [
                    'customer_id' => $plan->parent_customer_id,
                    'customer' => $customer,
                    'exchange_rate_sale' => $plan->getExchangeRate(),
                    'currency_type_id' => $plan->currency_type_id,
                    'date_of_issue' => $date_of_issue->format('Y-m-d'),
                    'series_id' => $serie->id,
                    "grade" => $plan->grade,
                    "section" => $plan->section,
                    "payments" => $payments,

                    "prefix" => "NV",
                    "items" => $item,

                    "establishment_id" => $stablisment->id,
                    'due_date' => $due_date->format('Y-m-d'),
                    'time_of_issue' => $time_of_issue,


                    "total_prepayment" => $plan->total_prepayment,
                    "total_charge" => $plan->total_charge,
                    "total_discount" => $plan->total_discount,
                    "total_free" => $plan->total_free,
                    "total_exportation" => $plan->total_exportation,
                    "total_taxed" => $plan->total_taxed,
                    "total_unaffected" => $plan->total_unaffected,
                    "total_exonerated" => $plan->total_exonerated,


                    "total_igv" => $plan->total_igv,
                    "total_base_isc" => $plan->total_base_isc,
                    "total_isc" => $plan->total_isc,
                    "total_base_other_taxes" => $plan->total_base_other_taxes,
                    "total_other_taxes" => $plan->total_other_taxes,
                    "total_taxes" => $plan->total_taxes,
                    "total_value" => $plan->total_value,
                    // "subtotal" => $plan->subtotal,
                    "total" => $plan->total,
                    "operation_type_id" => null,


                    "charges" => $plan->charges,
                    "discounts" => $plan->discounts,
                    "attributes" => $plan->attributes,
                    "guides" => [],
                    'user_rel_suscription_plan_id' => $plan->id,
                    'prepayments' => $plan->prepayments,


                ];
                $request = new SaleNoteRequest();
                $request->merge($data);

                // app/Http/Controllers/Tenant/SaleNoteController.php store
                $saleNoteController = new SaleNoteController();
                $saleNoteSaved = $saleNoteController->store($request);
                if (isset($saleNoteSaved['data']) && isset($saleNoteSaved['data']['id'])) {
                    $ids[] = (int)$saleNoteSaved['data']['id'];
                    $updateCustomerSaleNote = SaleNote::find((int)$saleNoteSaved['data']['id']);
                    // $updateCustomerSaleNote->customer = $parent;
                    $currentCustomer = $updateCustomerSaleNote->customer;
                    $currentCustomer->children = $child;
                    $updateCustomerSaleNote->customer = $currentCustomer;
                    $updateCustomerSaleNote->push();
                }

                /* return [
                'success' => true,
                'data' => [
                    'id' => $this->sale_note->id,
                ],
            ];*/
                if ($typerPeriod->period == 'Y') {
                    $start_date->addYear();
                } else {
                    $start_date->addMonth();

                }
            }
            return $ids;


        }

        /**
         * @return int|null
         */
        public function getQuantityPeriod(): ?int
        {
            return $this->quantity_period;
        }

        /**
         * @return \Modules\Suscription\Models\Tenant\CatPeriod|null
         */
        public function getCatPeriod(): ?CatPeriod
        {
            return $this->cat_period;
        }

        public function getExchangeRate()
        {
            if (empty($this->exchange_rate_sale)) {
                $date = Carbon::now()->format('Y-m-d');
                $rate = ExchangeRate::where([
                    'date' => $date
                ])->first();
                if ( !empty($rate)) return $rate->sale;
                return 0;
            }
            return $this->exchange_rate_sale;
        }

        public function getCollectionData($withDocuments = false)
        {
            $data = $this->toArray();
            $data['plan'] = $this->suscription_plan->getCollectionData();

            if ($withDocuments == true) {
                $data['sales_note'] = $this->getSalesNote();
                $data['invoices'] = $this->getInvoice();
            }
            return $data;
        }

        public function getSalesNote()
        {
            $sl = explode(',', $this->sale_notes);
            $salesNote = SaleNote::whereIn('id', $sl)->get()->transform(function ($row) {
                return $row->getCollectionData();
            });

            return $salesNote;
        }

        public function getInvoice()
        {
            $sl = explode(',', $this->sale_notes);
            $salesNote = SaleNote::whereIn('id', $sl)->select('document_id')->get()->pluck('document_id');
            $documents = Document::wherein('id', $salesNote)->get()->transform(function ($row) {
                return self::getPartialInvoiceData($row);
            });
            return $documents;
        }

        /**
         * Extraido de app/Http/Resources/Tenant/DocumentCollection.php
         * @param \App\Models\Tenant\Document $document
         *
         * @return array
         */
        public static function getPartialInvoiceData(Document $document)
        {
            $has_xml = true;
            $has_pdf = true;
            $has_cdr = false;
            $btn_note = false;
            $btn_guide = true; // Boton para generar guia
            $btn_resend = false;
            $btn_voided = false;
            $btn_consult_cdr = false;
            $btn_delete_doc_type_03 = false;
            $btn_constancy_detraction = false;

            $affected_document = null;

            if ($document->group_id === '01') {
                if ($document->state_type_id === '01') {
                    $btn_resend = true;
                }

                if ($document->state_type_id === '05') {
                    $has_cdr = true;
                    $btn_note = true;
                    $btn_resend = false;
                    $btn_voided = true;
                    $btn_consult_cdr = true;
                }

                if (in_array($document->document_type_id, ['07', '08'])) {
                    $btn_note = false;
                }
            }
            if ($document->group_id === '02') {
                if ($document->state_type_id === '05') {
                    $btn_note = true;
                    $btn_voided = true;
                }

                if (in_array($document->document_type_id, ['07', '08'])) {
                    $btn_note = false;
                }

                if ($document->document_type_id === '03' && config('tenant.delete_document_type_03')) {

                    if ($document->state_type_id === '01' && $document->doesntHave('summary_document')) {
                        $btn_delete_doc_type_03 = true;
                    }

                }

            }
            $btn_guide = $btn_note;
            if ($btn_guide === false && ($document->state_type_id === '01')) {
                // #750
                $btn_guide = true;
            }

            if (in_array($document->document_type_id, ['01', '03'])) {
                $btn_constancy_detraction = ($document->detraction) ? true : false;
            }

            $btn_recreate_document = config('tenant.recreate_document');

            $btn_change_to_registered_status = false;
            if ($document->state_type_id === '01') {
                $btn_change_to_registered_status = config('tenant.change_to_registered_status');
            }

            $total_payment = $document->payments->sum('payment');
            $balance = number_format($document->total - $total_payment, 2, ".", "");

            $message_regularize_shipping = null;

            if ($document->regularize_shipping) {
                $message_regularize_shipping = "Por regularizar: {$document->response_regularize_shipping->code} - {$document->response_regularize_shipping->description}";
            }
            $nvs = $document->getNvCollection();

            $order_note = $document->getOrderNoteCollection();
            // Regresa si se hn enviado correos
            $email_send_it = false;
            $email_send_it_array = [];


            return [

                'id' => $document->id,
                'group_id' => $document->group_id,
                'soap_type_id' => $document->soap_type_id,
                'soap_type_description' => $document->soap_type->description,
                'date_of_issue' => $document->date_of_issue->format('Y-m-d'),
                'date_of_due' => (in_array($document->document_type_id, ['01', '03'])) ? $document->invoice->date_of_due->format('Y-m-d') : null,
                'number' => $document->number_full,
                'customer_name' => $document->customer->name,
                'customer_number' => $document->customer->number,
                'customer_telephone' => $document->customer->telephone,
                'currency_type_id' => $document->currency_type_id,
                'total_exportation' => $document->total_exportation,
                'total_free' => $document->total_free,
                'total_unaffected' => $document->total_unaffected,
                'total_exonerated' => $document->total_exonerated,
                'total_taxed' => $document->total_taxed,
                'total_igv' => $document->total_igv,
                'total' => $document->total,
                'state_type_id' => $document->state_type_id,
                'state_type_description' => $document->state_type->description,
                'document_type_description' => $document->document_type->description,
                'document_type_id' => $document->document_type->id,
                'has_xml' => $has_xml,
                'has_pdf' => $has_pdf,
                'has_cdr' => $has_cdr,
                'download_xml' => $document->download_external_xml,
                'download_pdf' => $document->download_external_pdf,
                'download_cdr' => $document->download_external_cdr,
                'btn_voided' => $btn_voided,
                'btn_note' => $btn_note,
                'btn_guide' => $btn_guide,
//                'btn_ticket' => $btn_ticket,
                'btn_resend' => $btn_resend,
                'btn_consult_cdr' => $btn_consult_cdr,
                'btn_constancy_detraction' => $btn_constancy_detraction,
                'btn_recreate_document' => $btn_recreate_document,
                'btn_change_to_registered_status' => $btn_change_to_registered_status,
                'btn_delete_doc_type_03' => $btn_delete_doc_type_03,
                'send_server' => (bool)$document->send_server,
//                'voided' => $voided,
                'affected_document' => $affected_document,
//                'has_xml_voided' => $has_xml_voided,
//                'has_cdr_voided' => $has_cdr_voided,
//                'download_xml_voided' => $download_xml_voided,
//                'download_cdr_voided' => $download_cdr_voided,
                'shipping_status' => json_decode($document->shipping_status),
                'sunat_shipping_status' => json_decode($document->sunat_shipping_status),
                'query_status' => json_decode($document->query_status),
                'created_at' => $document->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $document->updated_at->format('Y-m-d H:i:s'),
                'user_name' => ($document->user) ? $document->user->name : '',
                'user_email' => ($document->user) ? $document->user->email : '',
                'user_id' => $document->user_id,
                'email_send_it' => $email_send_it,
                'email_send_it_array' => $email_send_it_array,
                'external_id' => $document->external_id,

                'notes' => (in_array($document->document_type_id, ['01', '03'])) ? $document->affected_documents->transform(function ($document) {
                    return [
                        'id' => $document->id,
                        'document_id' => $document->document_id,
                        'note_type_description' => ($document->note_type == 'credit') ? 'NC' : 'ND',
                        'description' => $document->document->number_full,
                    ];
                }) : null,
                'sales_note' => $nvs,
                'order_note' => $order_note,
                'balance' => $balance,
                'guides' => !empty($document->guides) ? (array)$document->guides : null,
                'message_regularize_shipping' => $message_regularize_shipping,
                'regularize_shipping' => (bool)$document->regularize_shipping,
                'purchase_order' => $document->purchase_order,
                'is_editable' => $document->is_editable,
            ];
        }

        public function getCustomerAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
        }

        public function setCustomerAttribute($value)
        {
            $this->attributes['customer'] = json_encode($value ?? []);
        }

        public function getChildrenCustomerAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
        }

        public function setChildrenCustomerAttribute($value)
        {
            $this->attributes['children_customer'] = json_encode($value ?? []);
        }

        public function getParentCustomerAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
        }

        public function setParentCustomerAttribute($value)
        {
            $this->attributes['parent_customer'] = json_encode($value ?? []);
        }

        public function getItemsAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
        }

        public function setItemsAttribute($value)
        {
            $this->attributes['items'] = json_encode($value ?? []);
        }

        /**
         * @param \Modules\Suscription\Models\Tenant\CatPeriod|null $cat_period
         *
         * @return UserRelSuscriptionPlan
         */
        public function setCatPeriod(?CatPeriod $cat_period): UserRelSuscriptionPlan
        {
            $this->cat_period = $cat_period;
            return $this;
        }

        /**
         * @return \Modules\Suscription\Models\Tenant\SuscriptionPlan|null
         */
        public function getSuscriptionPlan(): ?SuscriptionPlan
        {
            return $this->suscription_plan;
        }

        /**
         * @param \Modules\Suscription\Models\Tenant\SuscriptionPlan|null $suscription_plan
         *
         * @return UserRelSuscriptionPlan
         */
        public function setSuscriptionPlan(?SuscriptionPlan $suscription_plan): UserRelSuscriptionPlan
        {
            $this->suscription_plan = $suscription_plan;
            return $this;
        }

        /**
         * @return \App\Models\Tenant\User|null
         */
        public function getUser(): ?User
        {
            return $this->user;
        }

        /**
         * @param \App\Models\Tenant\User|null $user
         *
         * @return UserRelSuscriptionPlan
         */
        public function setUser(?User $user): UserRelSuscriptionPlan
        {
            $this->user = $user;
            return $this;
        }

        /**
         * @param int|null $quantity_period
         *
         * @return UserRelSuscriptionPlan
         */
        public function setQuantityPeriod(?int $quantity_period): UserRelSuscriptionPlan
        {
            $this->quantity_period = $quantity_period;
            return $this;
        }

        /**
         * @return \App\Models\Tenant\Person|null
         */
        public function getChildrenCustomerRelation(): ?Person
        {
            return $this->children_customer_relation;
        }

        /**
         * @param \App\Models\Tenant\Person|null $children_customer_relation
         *
         * @return UserRelSuscriptionPlan
         */
        public function setChildrenCustomerRelation(?Person $children_customer_relation): UserRelSuscriptionPlan
        {
            $this->children_customer_relation = $children_customer_relation;
            return $this;
        }

        /**
         * @return \App\Models\Tenant\Person|null
         */
        public function getCustomerRelation(): ?Person
        {
            return $this->customer_relation;
        }

        /**
         * @param \App\Models\Tenant\Person|null $customer_relation
         *
         * @return UserRelSuscriptionPlan
         */
        public function setCustomerRelation(?Person $customer_relation): UserRelSuscriptionPlan
        {
            $this->customer_relation = $customer_relation;
            return $this;
        }

        /**
         * @return \Carbon\Carbon|null
         */
        public function getAutomaticDateOfIssue(): ?Carbon
        {
            return $this->automatic_date_of_issue;
        }

        /**
         * @param \Carbon\Carbon|null $automatic_date_of_issue
         *
         * @return UserRelSuscriptionPlan
         */
        public function setAutomaticDateOfIssue(?Carbon $automatic_date_of_issue): UserRelSuscriptionPlan
        {
            $this->automatic_date_of_issue = $automatic_date_of_issue;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getDatesOfDocuments(): ?string
        {
            return $this->dates_of_documents;
        }

        /**
         * @param string|null $dates_of_documents
         *
         * @return UserRelSuscriptionPlan
         */
        public function setDatesOfDocuments(?string $dates_of_documents): UserRelSuscriptionPlan
        {
            $this->dates_of_documents = $dates_of_documents;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getDocuments(): ?string
        {
            return $this->documents;
        }

        /**
         * @param string|null $documents
         *
         * @return UserRelSuscriptionPlan
         */
        public function setDocuments(?string $documents): UserRelSuscriptionPlan
        {
            $this->documents = $documents;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getSaleNotes(): ?string
        {
            return $this->sale_notes;
        }

        /**
         * @param string|null $sale_notes
         *
         * @return UserRelSuscriptionPlan
         */
        public function setSaleNotes(?string $sale_notes): UserRelSuscriptionPlan
        {
            $this->sale_notes = $sale_notes;
            return $this;
        }

        /**
         * @return string
         */
        public function getCustomer(): string
        {
            return $this->customer;
        }

        /**
         * @param string $customer
         *
         * @return UserRelSuscriptionPlan
         */
        public function setCustomer(string $customer): UserRelSuscriptionPlan
        {
            $this->customer = $customer;
            return $this;
        }

        /**
         * @return string
         */
        public function getParentCustomer(): string
        {
            return $this->parent_customer;
        }

        /**
         * @param string $parent_customer
         *
         * @return UserRelSuscriptionPlan
         */
        public function setParentCustomer(string $parent_customer): UserRelSuscriptionPlan
        {
            $this->parent_customer = $parent_customer;
            return $this;
        }

        /**
         * @return bool
         */
        public function isApplyConcurrency(): bool
        {
            return $this->apply_concurrency;
        }

        /**
         * @param bool $apply_concurrency
         *
         * @return UserRelSuscriptionPlan
         */
        public function setApplyConcurrency(bool $apply_concurrency): UserRelSuscriptionPlan
        {
            $this->apply_concurrency = $apply_concurrency;
            return $this;
        }

        /**
         * @return bool
         */
        public function isEnabledConcurrency(): bool
        {
            return $this->enabled_concurrency;
        }

        /**
         * @param bool $enabled_concurrency
         *
         * @return UserRelSuscriptionPlan
         */
        public function setEnabledConcurrency(bool $enabled_concurrency): UserRelSuscriptionPlan
        {
            $this->enabled_concurrency = $enabled_concurrency;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getChildrenCustomerId(): ?int
        {
            return $this->children_customer_id;
        }

        /**
         * @return int|null
         */
        public function getParentCustomerId(): ?int
        {
            return $this->parent_customer_id;
        }

        /**
         * @return int|null
         */
        public function getCustomerId(): ?int
        {
            return $this->customer_id;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function cat_period()
        {
            return $this->belongsTo(CatPeriod::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function suscription_plan()
        {
            return $this->belongsTo(SuscriptionPlan::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function user()
        {
            return $this->belongsTo(User::class);
        }

        /**
         * @return int
         */
        public function getUserId(): int
        {
            return $this->user_id;
        }

        /**
         * @param int $user_id
         *
         * @return UserRelSuscriptionPlan
         */
        public function setUserId(int $user_id): UserRelSuscriptionPlan
        {
            $this->user_id = $user_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getSuscriptionPlanId(): int
        {
            return $this->suscription_plan_id;
        }

        /**
         * @param int $suscription_plan_id
         *
         * @return UserRelSuscriptionPlan
         */
        public function setSuscriptionPlanId(int $suscription_plan_id): UserRelSuscriptionPlan
        {
            $this->suscription_plan_id = $suscription_plan_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getCatPeriodId(): int
        {
            return $this->cat_period_id;
        }

        /**
         * @param int $cat_period_id
         *
         * @return UserRelSuscriptionPlan
         */
        public function setCatPeriodId(int $cat_period_id): UserRelSuscriptionPlan
        {
            $this->cat_period_id = $cat_period_id;
            return $this;
        }

        /**
         * @return bool
         */
        public function isEditable(): bool
        {
            return $this->editable;
        }

        /**
         * @param bool $editable
         *
         * @return UserRelSuscriptionPlan
         */
        public function setEditable(bool $editable): UserRelSuscriptionPlan
        {
            $this->editable = $editable;
            return $this;
        }

        /**
         * @return bool
         */
        public function isDeletable(): bool
        {
            return $this->deletable;
        }

        /**
         * @param bool $deletable
         *
         * @return UserRelSuscriptionPlan
         */
        public function setDeletable(bool $deletable): UserRelSuscriptionPlan
        {
            $this->deletable = $deletable;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getStartDate(): ?string
        {
            return $this->start_date;
        }

        /**
         * @param string|null $start_date
         *
         * @return UserRelSuscriptionPlan
         */
        public function setStartDate(?string $start_date): UserRelSuscriptionPlan
        {
            if (empty($start_date)) $start_date = Carbon::now()->format('Y-m-d');
            $this->start_date = $start_date;
            return $this;
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
         * @return UserRelSuscriptionPlan
         */
        public function setGrade(?string $grade): UserRelSuscriptionPlan
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
         * @return UserRelSuscriptionPlan
         */
        public function setSection(?string $section): UserRelSuscriptionPlan
        {
            $this->section = $section;
            return $this;
        }

    }
