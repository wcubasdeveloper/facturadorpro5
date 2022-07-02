<?php

    namespace Modules\Order\Models;

    use App\Models\Tenant\Catalogs\AffectationIgvType;
    use App\Models\Tenant\Catalogs\PriceType;
    use App\Models\Tenant\Catalogs\SystemIscType;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use App\Traits\AttributePerItems;
    use Eloquent;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Modules\Inventory\Models\Warehouse;


    /**
     * Modules\Order\Models\OrderNoteItem
     *
     * @package Modules\Order\Models
     * @property int                $id
     * @property int                $order_note_id
     * @property int                $item_id
     * @property float              $quantity
     * @property float              $unit_value
     * @property string             $affectation_igv_type_id
     * @property float              $total_base_igv
     * @property float              $percentage_igv
     * @property float              $total_igv
     * @property string|null        $system_isc_type_id
     * @property float              $total_base_isc
     * @property float              $percentage_isc
     * @property float              $total_isc
     * @property float              $total_base_other_taxes
     * @property float              $percentage_other_taxes
     * @property float              $total_other_taxes
     * @property float|null         $total_plastic_bag_taxes
     * @property float              $total_taxes
     * @property string             $price_type_id
     * @property float              $unit_price
     * @property float              $total_value
     * @property float              $total_charge
     * @property float              $total_discount
     * @property float              $total
     * @property string|null        $additional_information
     * @property int|null           $warehouse_id
     * @property string|null        $name_product_pdf
     * @property OrderNote          $order_note
     * @property Warehouse|null     $warehouse
     * @property AffectationIgvType $affectation_igv_type
     * @property mixed              $attributes
     * @property mixed              $charges
     * @property mixed              $discounts
     * @property mixed              $item
     * @property PriceType          $price_type
     * @property Item               $relation_item
     * @property SystemIscType      $system_isc_type
     * @method static Builder|OrderNoteItem newModelQuery()
     * @method static Builder|OrderNoteItem newQuery()
     * @method static Builder|OrderNoteItem query()
     * @method static Builder|OrderNoteItem whereDefaultState($params = [])
     * @method static Builder|OrderNoteItem wherePendingState($params = [])
     * @method static Builder|OrderNoteItem whereProcessedState($params = [])
     * @mixin Eloquent
     * @mixin ModelTenant
     */
    class OrderNoteItem extends ModelTenant
    {
        use AttributePerItems;

        public $timestamps = false;
        protected $with = [
            'affectation_igv_type',
            'system_isc_type',
            'price_type'
        ];
        protected $fillable = [
            'order_note_id',
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
            'warehouse_id',
            'total_plastic_bag_taxes',
            'additional_information',
            'name_product_pdf',
        ];
        protected $casts = [
            'order_note_id' => 'int',
            'item_id' => 'int',
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
            'total_plastic_bag_taxes' => 'float',
            'total_taxes' => 'float',
            'unit_price' => 'float',
            'total_value' => 'float',
            'total_charge' => 'float',
            'total_discount' => 'float',
            'total' => 'float',
            'warehouse_id' => 'int'
        ];

        /**
         * @return int
         */
        public function getOrderNoteId(): int
        {
            return (int)$this->order_note_id;
        }

        /**
         * @param int $order_note_id
         *
         * @return OrderNoteItem
         */
        public function setOrderNoteId(?int $order_note_id): OrderNoteItem
        {
            $this->order_note_id = (int)$order_note_id;
            return $this;
        }

        /**
         * @return int
         */
        public function getItemId(): int
        {
            return (int)$this->item_id;
        }

        /**
         * @param int $item_id
         *
         * @return OrderNoteItem
         */
        public function setItemId(?int $item_id): OrderNoteItem
        {
            $this->item_id = (int)$item_id;
            return $this;
        }

        /**
         * @return float
         */
        public function getQuantity(): float
        {
            return (float)$this->quantity;
        }

        /**
         * @param float $quantity
         *
         * @return OrderNoteItem
         */
        public function setQuantity(?float $quantity): OrderNoteItem
        {
            $this->quantity = (float)$quantity;
            return $this;
        }

        /**
         * @return float
         */
        public function getUnitValue(): float
        {
            return (float)$this->unit_value;
        }

        /**
         * @param float $unit_value
         *
         * @return OrderNoteItem
         */
        public function setUnitValue(?float $unit_value): OrderNoteItem
        {
            $this->unit_value = (float)$unit_value;
            return $this;
        }

        /**
         * @return string
         */
        public function getAffectationIgvTypeId(): string
        {
            return $this->affectation_igv_type_id;
        }

        /**
         * @param string $affectation_igv_type_id
         *
         * @return OrderNoteItem
         */
        public function setAffectationIgvTypeId(string $affectation_igv_type_id): OrderNoteItem
        {
            $this->affectation_igv_type_id = $affectation_igv_type_id;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotalBaseIgv(): float
        {
            return (float)$this->total_base_igv;
        }

        /**
         * @param float $total_base_igv
         *
         * @return OrderNoteItem
         */
        public function setTotalBaseIgv(?float $total_base_igv): OrderNoteItem
        {
            $this->total_base_igv = (float)$total_base_igv;
            return $this;
        }

        /**
         * @return float
         */
        public function getPercentageIgv(): float
        {
            return (float )$this->percentage_igv;
        }

        /**
         * @param float $percentage_igv
         *
         * @return OrderNoteItem
         */
        public function setPercentageIgv(?float $percentage_igv): OrderNoteItem
        {
            $this->percentage_igv = (float)$percentage_igv;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotalIgv(): float
        {
            return (float)$this->total_igv;
        }

        /**
         * @param float $total_igv
         *
         * @return OrderNoteItem
         */
        public function setTotalIgv(?float $total_igv): OrderNoteItem
        {
            $this->total_igv = (float)$total_igv;
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
         * @return OrderNoteItem
         */
        public function setSystemIscTypeId(?string $system_isc_type_id): OrderNoteItem
        {
            $this->system_isc_type_id = $system_isc_type_id;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotalBaseIsc(): float
        {
            return (float)$this->total_base_isc;
        }

        /**
         * @param float $total_base_isc
         *
         * @return OrderNoteItem
         */
        public function setTotalBaseIsc(?float $total_base_isc): OrderNoteItem
        {
            $this->total_base_isc = (float)$total_base_isc;
            return $this;
        }

        /**
         * @return float
         */
        public function getPercentageIsc(): float
        {
            return (float)$this->percentage_isc;
        }

        /**
         * @param float $percentage_isc
         *
         * @return OrderNoteItem
         */
        public function setPercentageIsc(?float $percentage_isc): OrderNoteItem
        {
            $this->percentage_isc = (float)$percentage_isc;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotalIsc(): float
        {
            return (float)$this->total_isc;
        }

        /**
         * @param float $total_isc
         *
         * @return OrderNoteItem
         */
        public function setTotalIsc(?float $total_isc): OrderNoteItem
        {
            $this->total_isc = (float)$total_isc;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotalBaseOtherTaxes(): float
        {
            return (float)$this->total_base_other_taxes;
        }

        /**
         * @param float $total_base_other_taxes
         *
         * @return OrderNoteItem
         */
        public function setTotalBaseOtherTaxes(?float $total_base_other_taxes): OrderNoteItem
        {
            $this->total_base_other_taxes = (float)$total_base_other_taxes;
            return $this;
        }

        /**
         * @return float
         */
        public function getPercentageOtherTaxes(): float
        {
            return (float)$this->percentage_other_taxes;
        }

        /**
         * @param float $percentage_other_taxes
         *
         * @return OrderNoteItem
         */
        public function setPercentageOtherTaxes(?float $percentage_other_taxes): OrderNoteItem
        {
            $this->percentage_other_taxes = (float)$percentage_other_taxes;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotalOtherTaxes(): float
        {
            return (float)$this->total_other_taxes;
        }

        /**
         * @param float $total_other_taxes
         *
         * @return OrderNoteItem
         */
        public function setTotalOtherTaxes(?float $total_other_taxes): OrderNoteItem
        {
            $this->total_other_taxes = (float)$total_other_taxes;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotalTaxes(): float
        {
            return (float)$this->total_taxes;
        }

        /**
         * @param float $total_taxes
         *
         * @return OrderNoteItem
         */
        public function setTotalTaxes(?float $total_taxes): OrderNoteItem
        {
            $this->total_taxes = (float)$total_taxes;
            return $this;
        }

        /**
         * @return string
         */
        public function getPriceTypeId(): string
        {
            return $this->price_type_id;
        }

        /**
         * @param string $price_type_id
         *
         * @return OrderNoteItem
         */
        public function setPriceTypeId(string $price_type_id): OrderNoteItem
        {
            $this->price_type_id = $price_type_id;
            return $this;
        }

        /**
         * @return float
         */
        public function getUnitPrice(): float
        {
            return (float)$this->unit_price;
        }

        /**
         * @param float $unit_price
         *
         * @return OrderNoteItem
         */
        public function setUnitPrice(?float $unit_price): OrderNoteItem
        {
            $this->unit_price = (float)$unit_price;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotalValue(): float
        {
            return (float)$this->total_value;
        }

        /**
         * @param float $total_value
         *
         * @return OrderNoteItem
         */
        public function setTotalValue(?float $total_value): OrderNoteItem
        {
            $this->total_value = (float)$total_value;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotalCharge(): float
        {
            return (float)$this->total_charge;
        }

        /**
         * @param float $total_charge
         *
         * @return OrderNoteItem
         */
        public function setTotalCharge(?float $total_charge): OrderNoteItem
        {
            $this->total_charge = (float)$total_charge;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotalDiscount(): float
        {
            return (float)$this->total_discount;
        }

        /**
         * @param float $total_discount
         *
         * @return OrderNoteItem
         */
        public function setTotalDiscount(?float $total_discount): OrderNoteItem
        {
            $this->total_discount = (float)$total_discount;
            return $this;
        }

        /**
         * @return float
         */
        public function getTotal(): float
        {
            return (float)$this->total;
        }

        /**
         * @param float $total
         *
         * @return OrderNoteItem
         */
        public function setTotal(?float $total): OrderNoteItem
        {
            $this->total = (float)$total;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getWarehouseId(): ?int
        {
            return (int)$this->warehouse_id;
        }

        /**
         * @param int|null $warehouse_id
         *
         * @return OrderNoteItem
         */
        public function setWarehouseId(?int $warehouse_id): OrderNoteItem
        {
            $this->warehouse_id = (int)$warehouse_id;
            return $this;
        }

        /**
         * @return OrderNote
         */
        public function getOrderNote(): OrderNote
        {
            return $this->order_note;
        }

        /**
         * @param OrderNote $order_note
         *
         * @return OrderNoteItem
         */
        public function setOrderNote(OrderNote $order_note): OrderNoteItem
        {
            $this->order_note = $order_note;
            return $this;
        }

        /**
         * @return Warehouse|null
         */
        public function getWarehouse(): ?Warehouse
        {
            return $this->warehouse;
        }

        /**
         * @param Warehouse|null $warehouse
         *
         * @return OrderNoteItem
         */
        public function setWarehouse(?Warehouse $warehouse): OrderNoteItem
        {
            $this->warehouse = $warehouse;
            return $this;
        }

        /**
         * @return AffectationIgvType
         */
        public function getAffectationIgvType(): AffectationIgvType
        {
            return $this->affectation_igv_type;
        }

        /**
         * @param AffectationIgvType $affectation_igv_type
         *
         * @return OrderNoteItem
         */
        public function setAffectationIgvType(AffectationIgvType $affectation_igv_type): OrderNoteItem
        {
            $this->affectation_igv_type = $affectation_igv_type;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getAttributes()
        {
            return $this->attributes;
        }

        /**
         * @param mixed $attributes
         *
         * @return OrderNoteItem
         */
        public function setAttributes($attributes)
        {
            $this->attributes = $attributes;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getCharges()
        {
            return $this->charges;
        }

        /**
         * @param mixed $charges
         *
         * @return OrderNoteItem
         */
        public function setCharges($charges)
        {
            $this->charges = $charges;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getDiscounts()
        {
            return $this->discounts;
        }

        /**
         * @param mixed $discounts
         *
         * @return OrderNoteItem
         */
        public function setDiscounts($discounts)
        {
            $this->discounts = $discounts;
            return $this;
        }

        /**
         * @return mixed
         */
        public function getItem()
        {
            return $this->item;
        }

        /**
         * @param mixed $item
         *
         * @return OrderNoteItem
         */
        public function setItem($item)
        {
            $this->item = $item;
            return $this;
        }

        /**
         * @return PriceType
         */
        public function getPriceType(): PriceType
        {
            return $this->price_type;
        }

        /**
         * @param PriceType $price_type
         *
         * @return OrderNoteItem
         */
        public function setPriceType(PriceType $price_type): OrderNoteItem
        {
            $this->price_type = $price_type;
            return $this;
        }

        /**
         * @return Item
         */
        public function getRelationItem(): Item
        {
            return $this->relation_item;
        }

        /**
         * @param Item $relation_item
         *
         * @return OrderNoteItem
         */
        public function setRelationItem(Item $relation_item): OrderNoteItem
        {
            $this->relation_item = $relation_item;
            return $this;
        }

        /**
         * @return SystemIscType
         */
        public function getSystemIscType(): SystemIscType
        {
            return $this->system_isc_type;
        }

        /**
         * @param SystemIscType $system_isc_type
         *
         * @return OrderNoteItem
         */
        public function setSystemIscType(SystemIscType $system_isc_type): OrderNoteItem
        {
            $this->system_isc_type = $system_isc_type;
            return $this;
        }

        public function getItemAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setItemAttribute($value)
        {
            $this->attributes['item'] = (is_null($value)) ? null : json_encode($value);
        }

        public function getAttributesAttribute($value)
        {
            return (is_null($value)) ? null : (object)json_decode($value);
        }

        public function setAttributesAttribute($value)
        {
            $this->attributes['attributes'] = (is_null($value)) ? null : json_encode($value);
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

        /**
         * @return BelongsTo
         */
        public function affectation_igv_type()
        {
            return $this->belongsTo(AffectationIgvType::class, 'affectation_igv_type_id');
        }

        /**
         * @return BelongsTo
         */
        public function system_isc_type()
        {
            return $this->belongsTo(SystemIscType::class, 'system_isc_type_id');
        }

        /**
         * @return BelongsTo
         */
        public function price_type()
        {
            return $this->belongsTo(PriceType::class, 'price_type_id');
        }


        /**
         * @return BelongsTo
         */
        public function order_note()
        {
            return $this->belongsTo(OrderNote::class, 'order_note_id');
        }


        /**
         * @param Builder $query
         * @param array   $params
         *
         * @return Builder
         */
        public function scopeWherePendingState(Builder $query, $params = [])
        {
            $query->whereHas('order_note', function ($q) use ($params) {
                if ($params['person_id']) {
                    $q->where('customer_id', $params['person_id']);
                } else {
                    $q->where('user_id', $params['seller_id']);
                }
                $q->whereTypeUser();
            });

            return $query;
        }


        /**
         * @param Builder $query
         * @param array   $params
         *
         * @return Builder
         */
        public function scopeWhereProcessedState(Builder $query, $params = [])
        {
            $query->whereHas('order_note', function ($q) use ($params) {
                $q->whereHas('documents');
                $q->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']]);
                if ($params['person_id']) {
                    $q->where('customer_id', $params['person_id']);
                } else {
                    $q->where('user_id', $params['seller_id']);

                }
                $q->whereTypeUser();
            });
            return $query;

        }

        /**
         * @param Builder $query
         * @param array   $params
         *
         * @return Builder
         */
        public function scopeWhereDefaultState(Builder $query, $params = [])
        {
            $query->whereHas('order_note', function ($q) use ($params) {
                $q->whereBetween($params['date_range_type_id'], [$params['date_start'], $params['date_end']]);
                if ($params['person_id']) {
                    $q->where('customer_id', $params['person_id']);
                } else {
                    $q->where('user_id', $params['seller_id']);
                }
                $q->whereTypeUser();
            });

            return $query;


        }

        /**
         * @return BelongsTo
         */
        public function warehouse()
        {
            return $this->belongsTo(Warehouse::class);
        }

        /**
         * @return BelongsTo
         */
        public function relation_item()
        {
            return $this->belongsTo(Item::class, 'item_id');
        }

        /**
         * @return float
         */
        public function getTotalPlasticBagTaxes(): float
        {
            return (float)$this->total_plastic_bag_taxes;
        }

        /**
         * @param float $total_plastic_bag_taxes
         *
         * @return OrderNoteItem
         */
        public function setTotalPlasticBagTaxes(?float $total_plastic_bag_taxes): OrderNoteItem
        {
            $this->total_plastic_bag_taxes = (float)$total_plastic_bag_taxes;
            return $this;
        }

        /**
         * @return string
         */
        public function getAdditionalInformation(): string
        {
            return $this->additional_information;
        }

        /**
         * @param string $additional_information
         *
         * @return OrderNoteItem
         */
        public function setAdditionalInformation(string $additional_information): OrderNoteItem
        {
            $this->additional_information = $additional_information;
            return $this;
        }

        /**
         * @param string $name_product_pdf
         *
         * @return OrderNoteItem
         */
        public function setNameProductPdf(string $name_product_pdf): OrderNoteItem
        {
            $this->name_product_pdf = $name_product_pdf;
            return $this;
        }

        /**
         * Devuelve el numero de la cantidad, si es int, devuelve el numero con 0 decimales
         *
         * @return mixed|string
         */
        public function getStringQty()
        {
            $int_qty = (int)$this->quantity;
            $qty = $this->quantity;
            if (is_int($qty)) {
                $qty = number_format($qty, 0);
            }
            return $qty;
        }

        /**
         * Devuelve unit_price formateado a string con N decimales
         *
         * @param int $decimal
         *
         * @return string
         */
        public function getStringUnitPrice($decimal = 2)
        {
            return number_format($this->unit_price, $decimal);
        }

        /**
         *  Devuelve total formateado a string con N decimales
         *
         * @param int $decimal
         *
         * @return string
         */
        public function getStringTotal($decimal = 2)
        {
            return number_format($this->total, $decimal);
        }

        /**
         *  Devuelve la descripcion del producto
         *
         * @return string
         */
        public function getTemplateDescription()
        {
            if (empty($this->name_product_pdf)) {
                return $this->item->description;
            }
            return $this->getNameProductPdf();

        }

        /**
         * @return string
         */
        public function getNameProductPdf(): string
        {
            return $this->name_product_pdf;
        }
        
        /**
         * Obtener lotes vendidos
         *
         * @return array
         */
        public function getSaleLotGroupCode()
        {
            if(isset($this->item->lots_group))
            {
                if(is_array($this->item->lots_group)) return collect($this->item->lots_group)->where('compromise_quantity', '>', 0)->pluck('code')->toArray();
            }

            return [];
        }

        /**
         * Obtener descripción de lotes vendidos
         * Usado en formato PDF
         *
         * @return string
         */
        public function getSaleLotGroupCodeDescription()
        {
            return implode('/', $this->getSaleLotGroupCode());
        }

    }
