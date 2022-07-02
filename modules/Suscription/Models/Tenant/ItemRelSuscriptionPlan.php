<?php

    /**
     */

    namespace Modules\Suscription\Models\Tenant;

    use App\Models\Tenant\Catalogs\AffectationIgvType;
    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Catalogs\PriceType;
    use App\Models\Tenant\Catalogs\SystemIscType;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Collection;

    /**
     * Class ItemRelSuscriptionPlan
     *
     * @property int                                                     $id
     * @property int|null                                                $item_id
     * @property int|null                                                $suscription_plan_id
     * @property Carbon|null                                             $created_at
     * @property Carbon|null                                             $updated_at
     * @property string|null                                             $item
     * @property float|null                                              $quantity
     * @property float|null                                              $unit_value
     * @property string|null                                             $affectation_igv_type_id
     * @property float|null                                              $total_base_igv
     * @property float|null                                              $percentage_igv
     * @property float|null                                              $total_igv
     * @property string|null                                             $system_isc_type_id
     * @property float|null                                              $total_base_isc
     * @property float|null                                              $percentage_isc
     * @property float|null                                              $total_isc
     * @property float|null                                              $total_base_other_taxes
     * @property float|null                                              $percentage_other_taxes
     * @property float|null                                              $total_other_taxes
     * @property float|null                                              $total_taxes
     * @property string|null                                             $price_type_id
     * @property float|null                                              $unit_price
     * @property float|null                                              $total_value
     * @property float|null                                              $total_charge
     * @property float|null                                              $total_discount
     * @property float|null                                              $total
     * @property string|null                                             $discounts
     * @property string|null                                             $charges
     * @property string|null                                             $additional_information
     * @property int|null                                                $warehouse_id
     * @property string|null                                             $name_product_pdf
     * @property Collection|Item[]                                       $relation_item
     * @method static Builder|ItemRelSuscriptionPlan newModelQuery()
     * @method static Builder|ItemRelSuscriptionPlan newQuery()
     * @method static Builder|ItemRelSuscriptionPlan query()
     * * @package App\Models
     * @property-read \Modules\Suscription\Models\Tenant\SuscriptionPlan $suscription_plan
     * @mixin \Eloquent
     * @property-read AffectationIgvType                                 $affectation_igv_type
     * @property-read PriceType                                          $price_type
     * @property-read SystemIscType                                      $system_isc_type
     */
    class ItemRelSuscriptionPlan extends ModelTenant
    {
        use UsesTenantConnection;

        protected $casts = [
            'item_id' => 'int',
            'suscription_plan_id' => 'int',
            'quantity' => 'float',
            'unit_value' => 'float',
            'total_base_igv' => 'float',
            'percentage_igv' => 'float',
            'total_igv' => 'float',
            'total_base_isc' => 'float',
            'percentage_isc' => 'float',
            'total_isc' => 'float',
            'total_base_other_taxes' => 'float',
            'percentage_other_taxes' => 'float',
            'total_other_taxes' => 'float',
            'total_taxes' => 'float',
            'unit_price' => 'float',
            'total_value' => 'float',
            'total_charge' => 'float',
            'total_discount' => 'float',
            'total' => 'float',
            'warehouse_id' => 'int'
        ];

        protected $fillable = [
            'item_id',
            'suscription_plan_id',
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
            'discounts',
            'charges',
            'additional_information',
            'warehouse_id',
            'name_product_pdf'
        ];

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getItemAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
        }

        /**
         * @param $value
         */
        public function setItemAttribute($value)
        {
            $this->attributes['item'] = ($value === null) ? null : json_encode($value);
        }

        /**
         * @param $value
         *
         * @return object|null
         */
        public function getAttributesAttribute($value)
        {
            return ($value === null) ? null : (object)json_decode($value);
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
        public function relation_item()
        {
            return $this->belongsTo(Item::class, 'item_id');
        }

        /**
         * @return float|null
         */
        public function getQuantity(): ?float
        {
            return $this->quantity;
        }

        /**
         * @param float|null $quantity
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setQuantity(?float $quantity): ItemRelSuscriptionPlan
        {
            $this->quantity = $quantity;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getUnitValue(): ?float
        {
            return $this->unit_value;
        }

        /**
         * @param float|null $unit_value
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setUnitValue(?float $unit_value): ItemRelSuscriptionPlan
        {
            $this->unit_value = $unit_value;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getAffectationIgvTypeId(): ?string
        {
            return $this->affectation_igv_type_id;
        }

        /**
         * @param string|null $affectation_igv_type_id
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setAffectationIgvTypeId(?string $affectation_igv_type_id): ItemRelSuscriptionPlan
        {
            $this->affectation_igv_type_id = $affectation_igv_type_id;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getTotalBaseIgv(): ?float
        {
            return $this->total_base_igv;
        }

        /**
         * @param float|null $total_base_igv
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setTotalBaseIgv(?float $total_base_igv): ItemRelSuscriptionPlan
        {
            $this->total_base_igv = $total_base_igv;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getPercentageIgv(): ?float
        {
            return $this->percentage_igv;
        }

        /**
         * @param float|null $percentage_igv
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setPercentageIgv(?float $percentage_igv): ItemRelSuscriptionPlan
        {
            $this->percentage_igv = $percentage_igv;
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
         * @return ItemRelSuscriptionPlan
         */
        public function setTotalIgv(?float $total_igv): ItemRelSuscriptionPlan
        {
            $this->total_igv = $total_igv;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getSystemIscTypeId(): ?string
        {
            return $this->system_isc_type_id;
        }

        /**
         * @param string|null $system_isc_type_id
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setSystemIscTypeId(?string $system_isc_type_id): ItemRelSuscriptionPlan
        {
            $this->system_isc_type_id = $system_isc_type_id;
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
         * @return ItemRelSuscriptionPlan
         */
        public function setTotalBaseIsc(?float $total_base_isc): ItemRelSuscriptionPlan
        {
            $this->total_base_isc = $total_base_isc;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getPercentageIsc(): ?float
        {
            return $this->percentage_isc;
        }

        /**
         * @param float|null $percentage_isc
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setPercentageIsc(?float $percentage_isc): ItemRelSuscriptionPlan
        {
            $this->percentage_isc = $percentage_isc;
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
         * @return ItemRelSuscriptionPlan
         */
        public function setTotalIsc(?float $total_isc): ItemRelSuscriptionPlan
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
         * @return ItemRelSuscriptionPlan
         */
        public function setTotalBaseOtherTaxes(?float $total_base_other_taxes): ItemRelSuscriptionPlan
        {
            $this->total_base_other_taxes = $total_base_other_taxes;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getPercentageOtherTaxes(): ?float
        {
            return $this->percentage_other_taxes;
        }

        /**
         * @param float|null $percentage_other_taxes
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setPercentageOtherTaxes(?float $percentage_other_taxes): ItemRelSuscriptionPlan
        {
            $this->percentage_other_taxes = $percentage_other_taxes;
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
         * @return ItemRelSuscriptionPlan
         */
        public function setTotalOtherTaxes(?float $total_other_taxes): ItemRelSuscriptionPlan
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
         * @return ItemRelSuscriptionPlan
         */
        public function setTotalTaxes(?float $total_taxes): ItemRelSuscriptionPlan
        {
            $this->total_taxes = $total_taxes;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getPriceTypeId(): ?string
        {
            return $this->price_type_id;
        }

        /**
         * @param string|null $price_type_id
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setPriceTypeId(?string $price_type_id): ItemRelSuscriptionPlan
        {
            $this->price_type_id = $price_type_id;
            return $this;
        }

        /**
         * @return float|null
         */
        public function getUnitPrice(): ?float
        {
            return $this->unit_price;
        }

        /**
         * @param float|null $unit_price
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setUnitPrice(?float $unit_price): ItemRelSuscriptionPlan
        {
            $this->unit_price = $unit_price;
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
         * @return ItemRelSuscriptionPlan
         */
        public function setTotalValue(?float $total_value): ItemRelSuscriptionPlan
        {
            $this->total_value = $total_value;
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
         * @return ItemRelSuscriptionPlan
         */
        public function setTotalCharge(?float $total_charge): ItemRelSuscriptionPlan
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
         * @return ItemRelSuscriptionPlan
         */
        public function setTotalDiscount(?float $total_discount): ItemRelSuscriptionPlan
        {
            $this->total_discount = $total_discount;
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
         * @return ItemRelSuscriptionPlan
         */
        public function setTotal(?float $total): ItemRelSuscriptionPlan
        {
            $this->total = $total;
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
         * @return ItemRelSuscriptionPlan
         */
        public function setDiscounts(?string $discounts): ItemRelSuscriptionPlan
        {
            $this->discounts = $discounts;
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
         * @return ItemRelSuscriptionPlan
         */
        public function setCharges(?string $charges): ItemRelSuscriptionPlan
        {
            $this->charges = $charges;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getAdditionalInformation(): ?string
        {
            return $this->additional_information;
        }

        /**
         * @param string|null $additional_information
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setAdditionalInformation(?string $additional_information): ItemRelSuscriptionPlan
        {
            $this->additional_information = $additional_information;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getWarehouseId(): ?int
        {
            return $this->warehouse_id;
        }

        /**
         * @param int|null $warehouse_id
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setWarehouseId(?int $warehouse_id): ItemRelSuscriptionPlan
        {
            $this->warehouse_id = $warehouse_id;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getNameProductPdf(): ?string
        {
            return $this->name_product_pdf;
        }

        /**
         * @param string|null $name_product_pdf
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setNameProductPdf(?string $name_product_pdf): ItemRelSuscriptionPlan
        {
            $this->name_product_pdf = $name_product_pdf;
            return $this;
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function suscription_plan()
        {
            return $this->belongsTo(SuscriptionPlan::class);
        }

        /**
         * @return int|null
         */
        public function getItemId(): ?int
        {
            return $this->item_id;
        }

        /**
         * @param int|null $item_id
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setItemId(?int $item_id): ItemRelSuscriptionPlan
        {
            $this->item_id = $item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getSuscriptionPlanId(): ?int
        {
            return $this->suscription_plan_id;
        }

        /**
         * @param int|null $suscription_plan_id
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setSuscriptionPlanId(?int $suscription_plan_id): ItemRelSuscriptionPlan
        {
            $this->suscription_plan_id = $suscription_plan_id;
            return $this;
        }

        /**
         * @return Item|null
         */
        public function getItem(): ?Item
        {
            return $this->item;
        }

        /**
         * @param Item|null $item
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setItem(?Item $item): ItemRelSuscriptionPlan
        {
            $this->item = $item;
            return $this;
        }

        /**
         * @return SuscriptionPlan|null
         */
        public function getSuscriptionPlan(): ?SuscriptionPlan
        {
            return $this->suscription_plan;
        }

        /**
         * @param SuscriptionPlan|null $suscription_plan
         *
         * @return ItemRelSuscriptionPlan
         */
        public function setSuscriptionPlan(?SuscriptionPlan $suscription_plan): ItemRelSuscriptionPlan
        {
            $this->suscription_plan = $suscription_plan;
            return $this;
        }

        /**
         * @return array
         */
        public function getCollectionData(CurrencyType  $currencyType = null)
        {
            $data = $this->toArray();
            $item = $this->relation_item;
            $relationItem = [];
            if ($item != null) {
                $relationItem = $item->getCollectionData();
            }
            $data = array_merge($data, $relationItem, $this->getTransformItem());
            $data['affectation_igv_type'] = $this->affectation_igv_type();
            $data['currency_type'] = $currencyType;


            return $data;
        }


        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function item()
        {
            return $this->belongsTo(Item::class);
        }

        public  function getTransformItem( $warehouse_id = 0)
        {
            $resource = $this->relation_item;

            $data_lots = [
                'lots' => [],
                // 'lots' => $resource->item_lots->where('has_sale', false)->where('warehouse_id', $warehouse_id)->transform(function($row) {
                //     return [
                //         'id' => $row->id,
                //         'series' => $row->series,
                //         'date' => $row->date,
                //         'item_id' => $row->item_id,
                //         'warehouse_id' => $row->warehouse_id,
                //         'has_sale' => (bool)$row->has_sale,
                //         'lot_code' => ($row->item_loteable_type) ? (isset($row->item_loteable->lot_code) ? $row->item_loteable->lot_code:null):null
                //     ];
                // })->values(),
                'series_enabled' => (bool)(($resource!= null)?$resource->series_enabled:false),
            ];

            return $data_lots;
        }
    }
