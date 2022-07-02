<?php

    namespace Modules\Sale\Models;

    use App\Models\Tenant\CashDocument;
    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\TechnicalServiceItem;
    use App\Models\Tenant\User;
    use App\Models\Tenant\SoapType;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\ModelTenant;
    // use App\Traits\SellerIdTrait;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasMany;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Collection;
    use App\Models\Tenant\{
        Document,
        SaleNote
    };

    /**
     * Class TechnicalService
     *
     * @property int                                  $id
     * @property int                                  $user_id
     * @property string                               $soap_type_id
     * @property int|null                             $establishment_id
     * @property string|null                          $establishment
     * @property int                                  $customer_id
     * @property string                               $customer
     * @property string|null                          $currency_type_id
     * @property string|null                          $payment_condition_id
     * @property string|null                          $payment_method_type_id
     * @property int|null                             $seller_id
     * @property float|null                           $exchange_rate_sale
     * @property float|null                           $total_prepayment
     * @property float|null                           $total_charge
     * @property float|null                           $total_discount
     * @property float|null                           $total_exportation
     * @property float|null                           $total_free
     * @property float|null                           $total_taxed
     * @property float|null                           $total_unaffected
     * @property float|null                           $total_exonerated
     * @property float|null                           $total_igv
     * @property float|null                           $total_igv_free
     * @property float|null                           $total_base_isc
     * @property float|null                           $total_isc
     * @property float|null                           $total_base_other_taxes
     * @property float|null                           $total_other_taxes
     * @property float|null                           $total_plastic_bag_taxes
     * @property float|null                           $total_taxes
     * @property float|null                           $total_value
     * @property float|null                           $subtotal
     * @property float|null                           $total
     * @property int|null                             $is_editable
     * @property string                               $cellphone
     * @property Carbon                               $date_of_issue
     * @property Carbon                               $time_of_issue
     * @property string                               $description
     * @property string                               $state
     * @property string                               $reason
     * @property string                               $serial_number
     * @property string|null                          $filename
     * @property float                                $cost
     * @property float                                $prepayment
     * @property string|null                          $activities
     * @property Carbon|null                          $created_at
     * @property Carbon|null                          $updated_at
     * @property string|null                          $brand
     * @property string|null                          $equipment
     * @property string|null                          $important_note
     * @property bool                                 $repair
     * @property bool                                 $warranty
     * @property bool                                 $maintenance
     * @property bool                                 $diagnosis
     * @property SoapType                             $soap_type
     * @property User                                 $user
     * @property Collection|CashDocument[]            $cash_documents
     * @property Collection|TechnicalServicePayment[] $technical_service_payments
     * @package App\Models
     */
    class TechnicalService extends ModelTenant
    {
        use UsesTenantConnection;
       // use SellerIdTrait;


        protected $perPage = 25;


        protected $fillable = [

            'user_id',
            'soap_type_id',
            'establishment_id',
            'establishment',
            'customer_id',
            'customer',
            'currency_type_id',
            'payment_condition_id',
            'payment_method_type_id',
            'seller_id',
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
            'total_plastic_bag_taxes',
            'total_taxes',
            'total_value',
            'subtotal',
            'total',
            'is_editable',
            'cellphone',
            'date_of_issue',
            'time_of_issue',
            'description',
            'state',
            'reason',
            'serial_number',
            'filename',
            'cost',
            'prepayment',
            'activities',
            'brand',
            'equipment',
            'important_note',
            'repair',
            'warranty',
            'maintenance',
            'diagnosis',
        ];

        protected $casts = [
            'user_id' => 'int',
            'establishment_id' => 'int',
            'customer_id' => 'int',
            'seller_id' => 'int',
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
            'total_plastic_bag_taxes' => 'float',
            'total_taxes' => 'float',
            'total_value' => 'float',
            'subtotal' => 'float',
            'total' => 'float',
            'is_editable' => 'int',
            'cost' => 'float',
            'prepayment' => 'float',
            'repair' => 'bool',
            'warranty' => 'bool',
            'maintenance' => 'bool',
            'diagnosis' => 'bool',
            'date_of_issue' => 'date',
        ];

        protected $dates = [
            'date_of_issue',
            // 'time_of_issue'
        ];

        public static function boot()
        {
            parent::boot();
            static::creating(function (self $item) {

                if (empty($item->establishment_id) && !empty($item->user_id)) {
                    $item->establishment_id = $item->user->establishment_id;
                }
                if(empty($item->currency_type_id)) $item->currency_type_id = 'PEN';
                //self::adjustSellerIdField($model);
            });
            static::retrieved(function (self $item) {

                if (empty($item->establishment_id) && !empty($item->user_id)) {
                    $item->establishment_id = $item->user->establishment_id;
                }
                if (empty($item->currency_type_id)) $item->currency_type_id = 'PEN';
            });

        }


        /**
         * @param $value
         *
         * @return object|null
         */
        public function getEstablishmentAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        /**
         * @param $value
         */
        public function setEstablishmentAttribute($value)
        {
            $this->attributes['establishment'] = (is_null($value)) ? null : json_encode($value);
        }

        /**
         * @return BelongsTo
         */
        public function customer()
        {
            return $this->belongsTo(Person::class, 'customer_id');
        }

        /**
         * @return BelongsTo
         */
        public function soap_type()
        {
            return $this->belongsTo(SoapType::class, 'soap_type_id', 'id');
        }

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getCustomerAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        /**
         * @param $value
         */
        public function setCustomerAttribute($value)
        {
            $this->attributes['customer'] = (is_null($value)) ? null : json_encode($value);
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
        public function person()
        {
            return $this->belongsTo(Person::class, 'customer_id');
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
         * @param $value
         */
        public function setImportantNoteAttribute($value)
        {
            $this->attributes['important_note'] = (is_null($value)) ? null : json_encode($value);
        }

        /**
         * @return HasMany
         */
        public function cash_documents()
        {
            return $this->hasMany(CashDocument::class);
        }

        /**
         * @return HasOne
         */
        public function document()
        {
            return $this->hasOne(Document::class);
        }

        /**
         * @return HasOne
         */
        public function sale_note()
        {
            return $this->hasOne(SaleNote::class);
        }

        /**
         * @param $value
         *
         * @return array|null
         */
        public function getImportantNoteAttribute($value)
        {
            return (is_null($value)) ? null : (array)json_decode($value);
        }

        /**
         * @return HasMany
         */
        public function technical_service_payments()
        {
            return $this->hasMany(TechnicalServicePayment::class);
        }

        /**
         * @return HasMany
         */
        public function payments()
        {
            return $this->technical_service_payments();
        }

        /**
         * @return HasMany
         */
        public function prepayments()
        {
            return $this->technical_service_payments();

        }

        /**
         * @return string
         */
        public function getNumberFullAttribute()
        {
            return "TS-{$this->id}";
        }

        /**
         * @return string
         */
        public function getCurrencyTypeIdAttribute()
        {
            return 'PEN';
        }

        /**
         * @return HasMany
         */
        public function technical_service_item()
        {
            return $this->hasMany(TechnicalServiceItem::class, 'technical_services_id');
        }

        /**
         * @return array
         */
        public function getCollectionData()
        {
            $items = $this->items->transform(function ($row) {
                /** @var TechnicalServiceItem $row */
                return $row->getCollectionData();
            });
            $total = $this->cost + $this->total;

            //docs asociados
            $has_document_sale_note = false;
            $number_document_sale_note = null;

            if($this->sale_note || $this->document){
                $has_document_sale_note = true;
                $number_document_sale_note = ($this->sale_note) ? $this->sale_note->number_full : $this->document->number_full;
            }

            $data = array_merge($this->toArray(), [
                'id' => $this->id,
                'soap_type_id' => $this->soap_type_id,
                'cellphone' => $this->cellphone,
                'serial_number' => $this->serial_number,
                'cost' => $this->cost,
                'total' => $this->total,
                'sum_total' => $total,
                'prepayment' => $this->prepayment,
                // 'balance' => $this->cost - $this->prepayment,
                'balance' => ($total) - collect($this->payments)->sum('payment'),
                'date_of_issue' => $this->date_of_issue->format('Y-m-d'),
                'customer_name' => $this->customer->name,
                'customer_number' => $this->customer->number,

                'customer_id' => $this->customer_id,
                'time_of_issue' => $this->time_of_issue,
                'description' => $this->description,
                'state' => $this->state,
                'reason' => $this->reason,
                'activities' => $this->activities,
                'brand' => $this->brand,
                'equipment' => $this->equipment,
                'important_note' => $this->important_note ?: [],
                'repair' => (bool)$this->repair,
                'warranty' => (bool)$this->warranty,
                'maintenance' => (bool)$this->maintenance,
                'diagnosis' => (bool)$this->diagnosis,
                'items' => $items,
                'payments' => $this->payments,

                'has_document_sale_note' => $has_document_sale_note,
                'number_document_sale_note' => $number_document_sale_note,

            ]);

            return $data;
        }

        /**
         * @param Company|null $company
         * @param Person|null  $customer
         *
         * @return array
         */
        public function getPdfData(Company $company = null, Person $customer = null)
        {

            $stablishment = $this->user->establishment;
            if ($company == null) {
                $company = Company::first();
            }
            if ($customer == null) {
                $customer = $this->person;
            }
            $data = [];
            //$data['company'] = $company->toArray();
            //$data['company']['logo'] = (!empty($company->getLogo())) ? $company->getLogo() : null;
            //$data['stablishment'] = $stablishment->toArray();
            //$data['customer'] = $customer->toArray();
            // $data['document'] = $this->toArray();
            $data['items'] = $this->items()->get()->transform(function ($row) {
                return $row->getCollectionData();
            });

            return $data;

        }

        /**
         * @return HasMany
         */
        public function items()
        {
            return $this->hasMany(TechnicalServiceItem::class, 'technical_services_id');
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
         * @return TechnicalService
         */
        public function setUserId(int $user_id): TechnicalService
        {
            $this->user_id = $user_id;
            return $this;
        }

        /**
         * @return string
         */
        public function getSoapTypeId(): string
        {
            return $this->soap_type_id;
        }

        /**
         * @param string $soap_type_id
         *
         * @return TechnicalService
         */
        public function setSoapTypeId(string $soap_type_id): TechnicalService
        {
            $this->soap_type_id = $soap_type_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getEstablishmentId(): ?int
        {
            return $this->establishment_id;
        }

        /**
         * @param int|null $establishment_id
         *
         * @return TechnicalService
         */
        public function setEstablishmentId(?int $establishment_id): TechnicalService
        {
            $this->establishment_id = $establishment_id;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getEstablishment(): ?string
        {
            return $this->establishment;
        }

        /**
         * @param string|null $establishment
         *
         * @return TechnicalService
         */
        public function setEstablishment(?string $establishment): TechnicalService
        {
            $this->establishment = $establishment;
            return $this;
        }

        /**
         * @return int
         */
        public function getCustomerId(): int
        {
            return $this->customer_id;
        }

        /**
         * @param int $customer_id
         *
         * @return TechnicalService
         */
        public function setCustomerId(int $customer_id): TechnicalService
        {
            $this->customer_id = $customer_id;
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
         * @return TechnicalService
         */
        public function setCustomer(string $customer): TechnicalService
        {
            $this->customer = $customer;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getCurrencyTypeId(): ?string
        {
            return $this->currency_type_id;
        }

        /**
         * @return BelongsTo
         */
        public function currency_type()
        {
            return $this->belongsTo(CurrencyType::class, 'currency_type_id');
        }

        /**
         * @param string|null $currency_type_id
         *
         * @return TechnicalService
         */
        public function setCurrencyTypeId(?string $currency_type_id): TechnicalService
        {
            $this->currency_type_id = $currency_type_id;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getPaymentConditionId(): ?string
        {
            return $this->payment_condition_id;
        }

        /**
         * @param string|null $payment_condition_id
         *
         * @return TechnicalService
         */
        public function setPaymentConditionId(?string $payment_condition_id): TechnicalService
        {
            $this->payment_condition_id = $payment_condition_id;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getPaymentMethodTypeId(): ?string
        {
            return $this->payment_method_type_id;
        }

        /**
         * @param string|null $payment_method_type_id
         *
         * @return TechnicalService
         */
        public function setPaymentMethodTypeId(?string $payment_method_type_id): TechnicalService
        {
            $this->payment_method_type_id = $payment_method_type_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getSellerId(): ?int
        {
            return $this->seller_id;
        }

        /**
         * @param int|null $seller_id
         *
         * @return TechnicalService
         */
        public function setSellerId(?int $seller_id): TechnicalService
        {
            $this->seller_id = $seller_id;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getExchangeRateSale(): ?float
        {
            return $this->exchange_rate_sale;
        }

        /**
         * @param float|null $exchange_rate_sale
         *
         * @return TechnicalService
         */
        public function setExchangeRateSale(?float $exchange_rate_sale): TechnicalService
        {
            $this->exchange_rate_sale = $exchange_rate_sale;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalPrepayment(): ?float
        {
            return $this->total_prepayment;
        }

        /**
         * @param float|null $total_prepayment
         *
         * @return TechnicalService
         */
        public function setTotalPrepayment(?float $total_prepayment): TechnicalService
        {
            $this->total_prepayment = $total_prepayment;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalCharge(): ?float
        {
            return $this->total_charge;
        }

        /**
         * @param float|null $total_charge
         *
         * @return TechnicalService
         */
        public function setTotalCharge(?float $total_charge): TechnicalService
        {
            $this->total_charge = $total_charge;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalDiscount(): ?float
        {
            return $this->total_discount;
        }

        /**
         * @param float|null $total_discount
         *
         * @return TechnicalService
         */
        public function setTotalDiscount(?float $total_discount): TechnicalService
        {
            $this->total_discount = $total_discount;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalExportation(): ?float
        {
            return $this->total_exportation;
        }

        /**
         * @param float|null $total_exportation
         *
         * @return TechnicalService
         */
        public function setTotalExportation(?float $total_exportation): TechnicalService
        {
            $this->total_exportation = $total_exportation;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalFree(): ?float
        {
            return $this->total_free;
        }

        /**
         * @param float|null $total_free
         *
         * @return TechnicalService
         */
        public function setTotalFree(?float $total_free): TechnicalService
        {
            $this->total_free = $total_free;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalTaxed(): ?float
        {
            return $this->total_taxed;
        }

        /**
         * @param float|null $total_taxed
         *
         * @return TechnicalService
         */
        public function setTotalTaxed(?float $total_taxed): TechnicalService
        {
            $this->total_taxed = $total_taxed;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalUnaffected(): ?float
        {
            return $this->total_unaffected;
        }

        /**
         * @param float|null $total_unaffected
         *
         * @return TechnicalService
         */
        public function setTotalUnaffected(?float $total_unaffected): TechnicalService
        {
            $this->total_unaffected = $total_unaffected;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalExonerated(): ?float
        {
            return $this->total_exonerated;
        }

        /**
         * @param float|null $total_exonerated
         *
         * @return TechnicalService
         */
        public function setTotalExonerated(?float $total_exonerated): TechnicalService
        {
            $this->total_exonerated = $total_exonerated;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalIgv(): ?float
        {
            return $this->total_igv;
        }

        /**
         * @param float|null $total_igv
         *
         * @return TechnicalService
         */
        public function setTotalIgv(?float $total_igv): TechnicalService
        {
            $this->total_igv = $total_igv;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalIgvFree(): ?float
        {
            return $this->total_igv_free;
        }

        /**
         * @param float|null $total_igv_free
         *
         * @return TechnicalService
         */
        public function setTotalIgvFree(?float $total_igv_free): TechnicalService
        {
            $this->total_igv_free = $total_igv_free;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalBaseIsc(): ?float
        {
            return $this->total_base_isc;
        }

        /**
         * @param float|null $total_base_isc
         *
         * @return TechnicalService
         */
        public function setTotalBaseIsc(?float $total_base_isc): TechnicalService
        {
            $this->total_base_isc = $total_base_isc;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalIsc(): ?float
        {
            return $this->total_isc;
        }

        /**
         * @param float|null $total_isc
         *
         * @return TechnicalService
         */
        public function setTotalIsc(?float $total_isc): TechnicalService
        {
            $this->total_isc = $total_isc;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalBaseOtherTaxes(): ?float
        {
            return $this->total_base_other_taxes;
        }

        /**
         * @param float|null $total_base_other_taxes
         *
         * @return TechnicalService
         */
        public function setTotalBaseOtherTaxes(?float $total_base_other_taxes): TechnicalService
        {
            $this->total_base_other_taxes = $total_base_other_taxes;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalOtherTaxes(): ?float
        {
            return $this->total_other_taxes;
        }

        /**
         * @param float|null $total_other_taxes
         *
         * @return TechnicalService
         */
        public function setTotalOtherTaxes(?float $total_other_taxes): TechnicalService
        {
            $this->total_other_taxes = $total_other_taxes;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalPlasticBagTaxes(): ?float
        {
            return $this->total_plastic_bag_taxes;
        }

        /**
         * @param float|null $total_plastic_bag_taxes
         *
         * @return TechnicalService
         */
        public function setTotalPlasticBagTaxes(?float $total_plastic_bag_taxes): TechnicalService
        {
            $this->total_plastic_bag_taxes = $total_plastic_bag_taxes;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalTaxes(): ?float
        {
            return $this->total_taxes;
        }

        /**
         * @param float|null $total_taxes
         *
         * @return TechnicalService
         */
        public function setTotalTaxes(?float $total_taxes): TechnicalService
        {
            $this->total_taxes = $total_taxes;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalValue(): ?float
        {
            return $this->total_value;
        }

        /**
         * @param float|null $total_value
         *
         * @return TechnicalService
         */
        public function setTotalValue(?float $total_value): TechnicalService
        {
            $this->total_value = $total_value;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getSubtotal(): ?float
        {
            return $this->subtotal;
        }

        /**
         * @param float|null $subtotal
         *
         * @return TechnicalService
         */
        public function setSubtotal(?float $subtotal): TechnicalService
        {
            $this->subtotal = $subtotal;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotal(): ?float
        {
            return $this->total;
        }

        /**
         * @param float|null $total
         *
         * @return TechnicalService
         */
        public function setTotal(?float $total): TechnicalService
        {
            $this->total = $total;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getIsEditable(): ?int
        {
            return $this->is_editable;
        }

        /**
         * @param int|null $is_editable
         *
         * @return TechnicalService
         */
        public function setIsEditable(?int $is_editable): TechnicalService
        {
            $this->is_editable = $is_editable;
            return $this;
        }

        /**
         * @return string
         */
        public function getCellphone(): string
        {
            return $this->cellphone;
        }

        /**
         * @param string $cellphone
         *
         * @return TechnicalService
         */
        public function setCellphone(string $cellphone): TechnicalService
        {
            $this->cellphone = $cellphone;
            return $this;
        }

        /**
         * @return Carbon
         */
        public function getDateOfIssue(): Carbon
        {
            return $this->date_of_issue;
        }

        /**
         * @param Carbon $date_of_issue
         *
         * @return TechnicalService
         */
        public function setDateOfIssue(Carbon $date_of_issue): TechnicalService
        {
            $this->date_of_issue = $date_of_issue;
            return $this;
        }

        /**
         * @return Carbon
         */
        public function getTimeOfIssue(): Carbon
        {
            return $this->time_of_issue;
        }

        /**
         * @param Carbon $time_of_issue
         *
         * @return TechnicalService
         */
        public function setTimeOfIssue(Carbon $time_of_issue): TechnicalService
        {
            $this->time_of_issue = $time_of_issue;
            return $this;
        }

        /**
         * @return string
         */
        public function getDescription(): string
        {
            return $this->description;
        }

        /**
         * @param string $description
         *
         * @return TechnicalService
         */
        public function setDescription(string $description): TechnicalService
        {
            $this->description = $description;
            return $this;
        }

        /**
         * @return string
         */
        public function getState(): string
        {
            return $this->state;
        }

        /**
         * @param string $state
         *
         * @return TechnicalService
         */
        public function setState(string $state): TechnicalService
        {
            $this->state = $state;
            return $this;
        }

        /**
         * @return string
         */
        public function getReason(): string
        {
            return $this->reason;
        }

        /**
         * @param string $reason
         *
         * @return TechnicalService
         */
        public function setReason(string $reason): TechnicalService
        {
            $this->reason = $reason;
            return $this;
        }

        /**
         * @return string
         */
        public function getSerialNumber(): string
        {
            return $this->serial_number;
        }

        /**
         * @param string $serial_number
         *
         * @return TechnicalService
         */
        public function setSerialNumber(string $serial_number): TechnicalService
        {
            $this->serial_number = $serial_number;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getFilename(): ?string
        {
            return $this->filename;
        }

        /**
         * @param string|null $filename
         *
         * @return TechnicalService
         */
        public function setFilename(?string $filename): TechnicalService
        {
            $this->filename = $filename;
            return $this;
        }

        /**
         * @return float
         */
        public function getCost(): float
        {
            return $this->cost;
        }

        /**
         * @param float $cost
         *
         * @return TechnicalService
         */
        public function setCost(float $cost): TechnicalService
        {
            $this->cost = $cost;
            return $this;
        }

        /**
         * @return float
         */
        public function getPrepayment(): float
        {
            return $this->prepayment;
        }

        /**
         * @param float $prepayment
         *
         * @return TechnicalService
         */
        public function setPrepayment(float $prepayment): TechnicalService
        {
            $this->prepayment = $prepayment;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getActivities(): ?string
        {
            return $this->activities;
        }

        /**
         * @param string|null $activities
         *
         * @return TechnicalService
         */
        public function setActivities(?string $activities): TechnicalService
        {
            $this->activities = $activities;
            return $this;
        }


        /**
         * @return string|null
         */
        public function getBrand(): ?string
        {
            return $this->brand;
        }

        /**
         * @param string|null $brand
         *
         * @return TechnicalService
         */
        public function setBrand(?string $brand): TechnicalService
        {
            $this->brand = $brand;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getEquipment(): ?string
        {
            return $this->equipment;
        }

        /**
         * @param string|null $equipment
         *
         * @return TechnicalService
         */
        public function setEquipment(?string $equipment): TechnicalService
        {
            $this->equipment = $equipment;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getImportantNote(): ?string
        {
            return $this->important_note;
        }

        /**
         * @param string|null $important_note
         *
         * @return TechnicalService
         */
        public function setImportantNote(?string $important_note): TechnicalService
        {
            $this->important_note = $important_note;
            return $this;
        }

        /**
         * @return bool
         */
        public function isRepair(): bool
        {
            return $this->repair;
        }

        /**
         * @param bool $repair
         *
         * @return TechnicalService
         */
        public function setRepair(bool $repair): TechnicalService
        {
            $this->repair = $repair;
            return $this;
        }

        /**
         * @return bool
         */
        public function isWarranty(): bool
        {
            return $this->warranty;
        }

        /**
         * @param bool $warranty
         *
         * @return TechnicalService
         */
        public function setWarranty(bool $warranty): TechnicalService
        {
            $this->warranty = $warranty;
            return $this;
        }

        /**
         * @return bool
         */
        public function isMaintenance(): bool
        {
            return $this->maintenance;
        }

        /**
         * @param bool $maintenance
         *
         * @return TechnicalService
         */
        public function setMaintenance(bool $maintenance): TechnicalService
        {
            $this->maintenance = $maintenance;
            return $this;
        }

        /**
         * @return bool
         */
        public function isDiagnosis(): bool
        {
            return $this->diagnosis;
        }

        /**
         * @param bool $diagnosis
         *
         * @return TechnicalService
         */
        public function setDiagnosis(bool $diagnosis): TechnicalService
        {
            $this->diagnosis = $diagnosis;
            return $this;
        }

        /**
         * @return SoapType
         */
        public function getSoapType(): SoapType
        {
            return $this->soap_type;
        }

        /**
         * @param SoapType $soap_type
         *
         * @return TechnicalService
         */
        public function setSoapType(SoapType $soap_type): TechnicalService
        {
            $this->soap_type = $soap_type;
            return $this;
        }

        /**
         * @return User
         */
        public function getUser(): User
        {
            return $this->user;
        }

        /**
         * @param User $user
         *
         * @return TechnicalService
         */
        public function setUser(User $user): TechnicalService
        {
            $this->user = $user;
            return $this;
        }

        /**
         * @return CashDocument[]|Collection
         */
        public function getCashDocuments()
        {
            return $this->cash_documents;
        }

        /**
         * @param CashDocument[]|Collection $cash_documents
         *
         * @return TechnicalService
         */
        public function setCashDocuments($cash_documents)
        {
            $this->cash_documents = $cash_documents;
            return $this;
        }

        /**
         * @return Collection|TechnicalServicePayment[]
         */
        public function getTechnicalServicePayments()
        {
            return $this->technical_service_payments;
        }

        /**
         * @param Collection|TechnicalServicePayment[] $technical_service_payments
         *
         * @return TechnicalService
         */
        public function setTechnicalServicePayments($technical_service_payments)
        {
            $this->technical_service_payments = $technical_service_payments;
            return $this;
        }


        /**
         * 
         * Obtener descripción del tipo de documento
         *
         * @return string
         */
        public function getDocumentTypeDescription()
        {
            return 'SERVICIO TÉCNICO';
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

    }
