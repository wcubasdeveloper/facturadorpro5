<?php

    /**
     */

    namespace Modules\FullSuscription\Models\Tenant;

    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\User;
    use Carbon\Carbon;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\BelongsToMany;
    use Illuminate\Database\Eloquent\Relations\HasMany;

    /**
     * Class SuscriptionPlan
     *
     * @property int                                 $id
     * @property int|null                            $cat_period_id
     * @property int|null                            $quantity_period
     * @property string                              $name
     * @property string|null                         $currency_type_id
     * @property string|null                         $payment_method_type_id
     * @property float|null                          $exchange_rate_sale
     * @property float|null                          $total_prepayment
     * @property float|null                          $total_charge
     * @property float|null                          $total_discount
     * @property float|null                          $total_exportation
     * @property float|null                          $total_free
     * @property float|null                          $total_taxed
     * @property float|null                          $total_unaffected
     * @property float|null                          $total_exonerated
     * @property float|null                          $total_igv
     * @property float|null                          $total_igv_free
     * @property float|null                          $total_base_isc
     * @property float|null                          $total_isc
     * @property float|null                          $total_base_other_taxes
     * @property float|null                          $total_other_taxes
     * @property float|null                          $total_taxes
     * @property float|null                          $total_value
     * @property string|null                         $charges
     * @property string|null                         $attributes
     * @property string|null                         $discounts
     * @property string|null                         $prepayments
     * @property string|null                         $related
     * @property string|null                         $perception
     * @property string|null                         $detraction
     * @property string|null                         $legends
     * @property string|null                         $terms_condition
     * @property string                              $description
     * @property float|null                          $total
     * @property Carbon|null                         $created_at
     * @property Carbon|null                         $updated_at
     * @property CatPeriod|null                      $cat_period
     * @property Collection|ItemRelSuscriptionPlan[] $items
     * @property Collection|User[]                   $users
     * @property-read int|null                       $items_count
     * @property-read int|null                       $users_count
     * @method static Builder|SuscriptionPlan newModelQuery()
     * @method static Builder|SuscriptionPlan newQuery()
     * @method static Builder|SuscriptionPlan query()
     * @mixin Eloquent
     * @package App\Models\Tenant\ModelTenant
     * @property-read CurrencyType                   $currency_type
     */
    class SuscriptionPlan extends ModelTenant
    {
        use UsesTenantConnection;

        protected $perPage = 25;

        protected $casts = [
            'quantity_period' => 'int',
            'cat_period_id' => 'int',
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
            'total' => 'float'
        ];

        protected $fillable = [
            'quantity_period',
            'cat_period_id',
            'name',
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
            'terms_condition',
            'description',
            'total'
        ];


        /**
         * @param $value
         *
         * @return null
         */
        public function getAttributesAttribute($value)
        {
            return ($value === null) ? null : json_decode($value);
        }

        /**
         * @param $value
         */
        public function setAttributesAttribute($value)
        {
            $this->attributes['attributes'] = ($value === null) ? null : json_encode($value);
        }

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getChargesAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
        }

        /**
         * @param $value
         */
        public function setChargesAttribute($value)
        {
            $this->attributes['charges'] = ($value === null) ? null : json_encode($value);
        }

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getDiscountsAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
        }

        /**
         * @param $value
         */
        public function setDiscountsAttribute($value)
        {
            $this->attributes['discounts'] = ($value === null) ? null : json_encode($value);
        }

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getPrepaymentsAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
        }

        /**
         * @param $value
         */
        public function setPrepaymentsAttribute($value)
        {
            $this->attributes['prepayments'] = ($value === null) ? null : json_encode($value);
        }

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getRelatedAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
        }

        /**
         * @param $value
         */
        public function setRelatedAttribute($value)
        {
            $this->attributes['related'] = ($value === null) ? null : json_encode($value);
        }

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getPerceptionAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
        }

        /**
         * @param $value
         */
        public function setPerceptionAttribute($value)
        {
            $this->attributes['perception'] = ($value === null) ? null : json_encode($value);
        }

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getDetractionAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
        }

        /**
         * @param $value
         */
        public function setDetractionAttribute($value)
        {
            $this->attributes['detraction'] = ($value === null) ? null : json_encode($value);
        }

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getLegendsAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
        }

        /**
         * @param $value
         */
        public function setLegendsAttribute($value)
        {
            $this->attributes['legends'] = ($value === null) ? null : json_encode($value);
        }

        /**
         * @return int|null
         */
        public function getCatPeriodId(): ?int
        {
            return $this->cat_period_id;
        }

        /**
         * @param int|null $cat_period_id
         *
         * @return SuscriptionPlan
         */
        public function setCatPeriodId(?int $cat_period_id): SuscriptionPlan
        {
            $this->cat_period_id = $cat_period_id;
            return $this;
        }


        /**
         * @return int|null
         */
        public function getQuantityPeriod(): ?int
        {
            return $this->quantity_period;
        }

        /**
         * @param int|null $quantity_period
         *
         * @return SuscriptionPlan
         */
        public function setQuantityPeriod(?int $quantity_period): SuscriptionPlan
        {
            $this->quantity_period = $quantity_period;
            return $this;
        }

        /**
         * @return CatPeriod
         */
        public function getCatPeriod(): CatPeriod
        {
            return CatPeriod::find($this->cat_period_id);
        }

        /**
         * @param CatPeriod $cat_period
         *
         * @return SuscriptionPlan
         */
        public function setCatPeriod(CatPeriod $cat_period): SuscriptionPlan
        {
            $this->cat_period_id = $cat_period->id;
            return $this;
        }

        /**
         * @return BelongsTo
         */
        public function cat_period()
        {
            return $this->belongsTo(CatPeriod::class);
        }

        /**
         * @return HasMany
         */
        public function items()
        {
            return $this->hasMany(ItemRelSuscriptionPlan::class, 'suscription_plan_id');

            /*
            return $this->belongsToMany(Item::class, 'item_rel_suscription_plans')
                ->withPivot('id')
                ->withTimestamps();
            */
        }

        /**
         * @return BelongsToMany
         */
        public function users()
        {
            return $this->belongsToMany(User::class, 'user_rel_suscription_plans')
                ->withPivot('id', 'cat_period_id', 'items_text', 'items', 'editable', 'deletable', 'start_date')
                ->withTimestamps();
        }

        /**
         * @return string
         */
        public function getName(): string
        {
            return $this->name;
        }


        /**
         * @param string $name
         *
         * @return $this
         */
        public function setName(string $name): SuscriptionPlan
        {
            $this->name = ucfirst(trim($name));
            return $this;
        }

        /**
         * @return string|null
         */
        public function getDescription(): ?string
        {
            return $this->description;
        }

        /**
         * @param string|null $description
         *
         * @return SuscriptionPlan
         */
        public function setDescription(?string $description): SuscriptionPlan
        {
            $this->description = $description;
            return $this;
        }


        /**
         * @return float|null
         */
        public function getTotal(): ?float
        {
            return (float)$this->total;
        }

        /**
         * @param float|null $total
         *
         * @return SuscriptionPlan
         */
        public function setTotal(?float $total): SuscriptionPlan
        {
            $this->total = (float)$total;
            return $this;
        }

        /**
         * @return array
         */
        public function getCollectionData()
        {


            $currencyType = $this->currency_type;
            if (empty($this->currency_type_id)) $currencyType = CurrencyType::find('PEN');

            $items = $this->items->transform(function ($item) use ($currencyType) {
                return $item->getCollectionData($currencyType);
            });
            $data = [
                'id' => $this->id,
                'cat_period_id' => $this->cat_period_id,
                'name' => $this->name,
                'items' => $items,
                'description' => $this->description,
                'period' => $this->cat_period->name,
                'currency_type' => $currencyType,
                'periods' => $this->cat_period->period,

            ];
            $data['hasSuscription'] = (bool)count(UserRelSuscriptionPlan::where('suscription_plan_id', $this->id)->get()) > 0;
            $data = array_merge($data, $this->toArray());
            return $data;
        }

        public function currency_type(): BelongsTo
        {
            return $this->belongsTo(CurrencyType::class, 'currency_type_id');
        }

        /**
         * @return string|null
         */
        public function getCurrencyTypeId(): ?string
        {
            return $this->currency_type_id;
        }

        /**
         * @param string|null $currency_type_id
         *
         * @return SuscriptionPlan
         */
        public function setCurrencyTypeId(?string $currency_type_id): SuscriptionPlan
        {
            $this->currency_type_id = $currency_type_id;
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
         * @return SuscriptionPlan
         */
        public function setPaymentMethodTypeId(?string $payment_method_type_id): SuscriptionPlan
        {
            $this->payment_method_type_id = $payment_method_type_id;
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
         * @return SuscriptionPlan
         */
        public function setExchangeRateSale(?float $exchange_rate_sale): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalPrepayment(?float $total_prepayment): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalCharge(?float $total_charge): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalDiscount(?float $total_discount): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalExportation(?float $total_exportation): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalFree(?float $total_free): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalTaxed(?float $total_taxed): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalUnaffected(?float $total_unaffected): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalExonerated(?float $total_exonerated): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalIgv(?float $total_igv): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalIgvFree(?float $total_igv_free): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalBaseIsc(?float $total_base_isc): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalIsc(?float $total_isc): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalBaseOtherTaxes(?float $total_base_other_taxes): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalOtherTaxes(?float $total_other_taxes): SuscriptionPlan
        {
            $this->total_other_taxes = $total_other_taxes;
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
         * @return SuscriptionPlan
         */
        public function setTotalTaxes(?float $total_taxes): SuscriptionPlan
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
         * @return SuscriptionPlan
         */
        public function setTotalValue(?float $total_value): SuscriptionPlan
        {
            $this->total_value = $total_value;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getCharges(): ?string
        {
            return $this->charges;
        }

        /**
         * @param string|null $charges
         *
         * @return SuscriptionPlan
         */
        public function setCharges(?string $charges): SuscriptionPlan
        {
            $this->charges = $charges;
            return $this;
        }


        /**
         * @return string|null
         */
        public function getDiscounts(): ?string
        {
            return $this->discounts;
        }

        /**
         * @param string|null $discounts
         *
         * @return SuscriptionPlan
         */
        public function setDiscounts(?string $discounts): SuscriptionPlan
        {
            $this->discounts = $discounts;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getPrepayments(): ?string
        {
            return $this->prepayments;
        }

        /**
         * @param string|null $prepayments
         *
         * @return SuscriptionPlan
         */
        public function setPrepayments(?string $prepayments): SuscriptionPlan
        {
            $this->prepayments = $prepayments;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getRelated(): ?string
        {
            return $this->related;
        }

        /**
         * @param string|null $related
         *
         * @return SuscriptionPlan
         */
        public function setRelated(?string $related): SuscriptionPlan
        {
            $this->related = $related;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getPerception(): ?string
        {
            return $this->perception;
        }

        /**
         * @param string|null $perception
         *
         * @return SuscriptionPlan
         */
        public function setPerception(?string $perception): SuscriptionPlan
        {
            $this->perception = $perception;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getDetraction(): ?string
        {
            return $this->detraction;
        }

        /**
         * @param string|null $detraction
         *
         * @return SuscriptionPlan
         */
        public function setDetraction(?string $detraction): SuscriptionPlan
        {
            $this->detraction = $detraction;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getLegends(): ?string
        {
            return $this->legends;
        }

        /**
         * @param string|null $legends
         *
         * @return SuscriptionPlan
         */
        public function setLegends(?string $legends): SuscriptionPlan
        {
            $this->legends = $legends;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getTermsCondition(): ?string
        {
            return $this->terms_condition;
        }

        /**
         * @param string|null $terms_condition
         *
         * @return SuscriptionPlan
         */
        public function setTermsCondition(?string $terms_condition): SuscriptionPlan
        {
            $this->terms_condition = $terms_condition;
            return $this;
        }

    }
