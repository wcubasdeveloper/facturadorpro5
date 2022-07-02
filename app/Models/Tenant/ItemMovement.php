<?php


    namespace App\Models\Tenant;


    use Carbon\Carbon;
    use DB;
    use ErrorException;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Database\Eloquent\Relations\BelongsTo;
    use Illuminate\Database\Eloquent\Relations\HasOne;
    use Modules\Expense\Models\Expense;
    use Modules\Expense\Models\ExpenseItem;
    use Modules\Inventory\Models\Devolution;
    use Modules\Inventory\Models\DevolutionItem;
    use Modules\Order\Models\OrderForm;
    use Modules\Order\Models\OrderFormItem;
    use Modules\Order\Models\OrderNote;
    use Modules\Order\Models\OrderNoteItem;
    use Modules\Purchase\Models\FixedAssetItem;
    use Modules\Purchase\Models\FixedAssetPurchase;
    use Modules\Purchase\Models\FixedAssetPurchaseItem;
    use Modules\Purchase\Models\PurchaseOrder;
    use Modules\Purchase\Models\PurchaseOrderItem;
    use Modules\Purchase\Models\PurchaseQuotation;
    use Modules\Purchase\Models\PurchaseQuotationItem;
    use Modules\Sale\Models\Contract;
    use Modules\Sale\Models\ContractItem;
    use Modules\Sale\Models\SaleOpportunity;
    use Modules\Sale\Models\SaleOpportunityItem;
    use Modules\Sale\Models\TechnicalService;

    /**
     * Class ItemMovement
     *
     * @property int                            $id
     * @property int|null                       $item_id
     * @property float|null                     $quantity
     * @property string|null                    $date_of_movement
     * @property int|null                       $contract_item_id
     * @property bool                           $countable
     * @property int|null                       $establishment_id
     * @property int|null                       $devolution_item_id
     * @property int|null                       $dispatch_item_id
     * @property int|null                       $document_item_id
     * @property int|null                       $expense_item_id
     * @property int|null                       $fixed_asset_item_id
     * @property int|null                       $fixed_asset_purchase_item_id
     * @property int|null                       $order_form_item_id
     * @property int|null                       $order_note_item_id
     * @property int|null                       $purchase_item_id
     * @property int|null                       $purchase_order_item_id
     * @property int|null                       $purchase_quotation_item_id
     * @property int|null                       $quotation_item_id
     * @property int|null                       $sale_note_item_id
     * @property int|null                       $sale_opportunity_item_id
     * @property int|null                       $technical_service_item_id
     * @property Carbon|null                    $created_at
     * @property Carbon|null                    $updated_at
     * @property ContractItem|null              $contract_item
     * @property DevolutionItem|null            $devolution_item
     * @property DispatchItem|null              $dispatch_item
     * @property DocumentItem|null              $document_item
     * @property ExpenseItem|null               $expense_item
     * @property FixedAssetItem|null            $fixed_asset_item
     * @property FixedAssetPurchaseItem|null    $fixed_asset_purchase_item
     * @property OrderFormItem|null             $order_form_item
     * @property OrderNoteItem|null             $order_note_item
     * @property PurchaseItem|null              $purchase_item
     * @property PurchaseOrderItem|null         $purchase_order_item
     * @property PurchaseQuotationItem|null     $purchase_quotation_item
     * @property QuotationItem|null             $quotation_item
     * @property SaleNoteItem|null              $sale_note_item
     * @property SaleOpportunityItem|null       $sale_opportunity_item
     * @property TechnicalServiceItem|null      $technical_service_item
     * @property Item|null                      $item
     * @package App\Models
     * @mixin ModelTenant
     * @property-read Establishment             $establishment
     * @method static Builder|ItemMovement byItem($item = 0)
     * @method static Builder|ItemMovement newModelQuery()
     * @method static Builder|ItemMovement newQuery()
     * @method static Builder|ItemMovement onlyCountable()
     * @method static Builder|ItemMovement query()
     * @property-read ItemMovementRelExtra|null $item_movement_rel_extra
     */
    class ItemMovement extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'item_movement';

        protected $casts = [
            'item_id' => 'int',
            'quantity' => 'float',
            'contract_item_id' => 'int',
            'devolution_item_id' => 'int',
            'establishment_id' => 'int',
            'dispatch_item_id' => 'int',
            'document_item_id' => 'int',
            'expense_item_id' => 'int',
            'fixed_asset_item_id' => 'int',
            'fixed_asset_purchase_item_id' => 'int',
            'order_form_item_id' => 'int',
            'order_note_item_id' => 'int',
            'purchase_item_id' => 'int',
            'purchase_order_item_id' => 'int',
            'purchase_quotation_item_id' => 'int',
            'quotation_item_id' => 'int',
            'sale_note_item_id' => 'int',
            'sale_opportunity_item_id' => 'int',
            'countable' => 'bool',
            'technical_service_item_id' => 'int',
        ];

        protected $fillable = [
            'item_id',
            'quantity',
            'date_of_movement',
            'countable',
            'contract_item_id',
            'devolution_item_id',
            'establishment_id',
            'dispatch_item_id',
            'document_item_id',
            'expense_item_id',
            'fixed_asset_item_id',
            'fixed_asset_purchase_item_id',
            'order_form_item_id',
            'order_note_item_id',
            'purchase_item_id',
            'purchase_order_item_id',
            'purchase_quotation_item_id',
            'quotation_item_id',
            'sale_note_item_id',
            'sale_opportunity_item_id',
            'technical_service_item_id'
        ];

        public static function getElementItemIdFromClass($modelClass, $item_id, $extra = [])
        {
            $q = self::query();
            if (!empty($item_id)) {
                $q->where('item_movement.item_id', $item_id);
            }
            if ($modelClass == DevolutionItem::class) {
                $q->where('devolution_item_id', '!=', 0)->select(DB::raw('item_movement.devolution_item_id as table_id'));
            } elseif (($modelClass == DispatchItem::class)) {
                $q->where('dispatch_item_id', '!=', 0)->select(DB::raw('item_movement.dispatch_item_id as table_id'));
            } elseif (($modelClass == DocumentItem::class)) {
                $q->where('document_item_id', '!=', 0)->select(DB::raw('item_movement.document_item_id as table_id'));
            } elseif (($modelClass == OrderFormItem::class)) {
                $q->where('order_form_item_id', '!=', 0)->select(DB::raw('item_movement.order_form_item_id as table_id'));
            } elseif (($modelClass == OrderNoteItem::class)) {
                $q->where('order_note_item_id', '!=', 0)->select(DB::raw('item_movement.order_note_item_id as table_id'));
            } elseif (($modelClass == PurchaseItem::class)) {
                $q->where('purchase_item', '!=', 0)->select(DB::raw('item_movement.purchase_item as table_id'));
            } elseif ($modelClass == PurchaseOrderItem::class) {
                $q->where('purchase_order_item_id', '!=', 0)->select(DB::raw('item_movement.purchase_order_item_id as table_id'));
            } elseif ($modelClass == PurchaseQuotationItem::class) {
                $q->where('purchase_quotation_item_id', '!=', 0)->select(DB::raw('item_movement.purchase_quotation_item_id as table_id'));
            } elseif ($modelClass == QuotationItem::class) {
                $q->where('quotation_item_id', '!=', 0)->select(DB::raw('item_movement.quotation_item_id as table_id'));
            } elseif ($modelClass == SaleNoteItem::class) {
                $q->where('sale_note_item_id', '!=', 0)->select(DB::raw('item_movement.sale_note_item_id as table_id'));
            } elseif ($modelClass == SaleOpportunityItem::class) {
                $q->where('sale_opportunity_item_id', '!=', 0)->select(DB::raw('item_movement.sale_opportunity_item_id as table_id'));
            } elseif ($modelClass == TechnicalServiceItem::class) {
                $q->where('technical_service_item_id', '!=', 0)->select(DB::raw('item_movement.technical_service_item_id as table_id'));
            } elseif ($modelClass == FixedAssetItem::class) {
                $q->where('fixed_asset_purchase_item_id', '!=', 0)->select(DB::raw('item_movement.fixed_asset_purchase_item_id as table_id'));
            } else {
                return [];
            }
            /** @var Builder $q */
            self::GetItemMovementRelExtra($q, $extra);
            $totales = $q->distinct()->get()->pluck('table_id');
            return $totales;

        }

        /**
         * Filtra los registros si llega a tener datos extra
         *
         * @param Builder $query
         * @param array   $extra
         */
        protected static function GetItemMovementRelExtra(&$query, $extra = [])
        {


            if (count($extra) != 0) {
                $color = $extra['colors'] ?? null;
                $CatItemUnitsPerPackage = $extra['CatItemUnitsPerPackage'] ?? null;
                $CatItemMoldProperty = $extra['CatItemMoldProperty'] ?? null;
                $CatItemUnitBusiness = $extra['CatItemUnitBusiness'] ?? null;
                $CatItemStatus = $extra['CatItemStatus'] ?? null;
                $CatItemPackageMeasurement = $extra['CatItemPackageMeasurement'] ?? null;
                $CatItemMoldCavity = $extra['CatItemMoldCavity'] ?? null;
                $CatItemProductFamily = $extra['CatItemProductFamily'] ?? null;
                $CatItemSize = $extra['CatItemSize'] ?? null;

                if (

                    !empty($CatItemStatus) ||
                    !empty($CatItemUnitBusiness) ||
                    !empty($CatItemMoldCavity) ||
                    !empty($CatItemPackageMeasurement) ||
                    !empty($CatItemUnitsPerPackage) ||
                    !empty($CatItemMoldProperty) ||
                    !empty($CatItemProductFamily) ||
                    !empty($color) ||
                    !empty($CatItemSize)
                ) {
                    $query->join('item_movement_rel_extra', 'item_movement_rel_extra.item_movement_id', '=', 'item_movement.id');

                    if ($CatItemStatus != null)
                        $query->where('item_movement_rel_extra.item_status_id', $CatItemStatus);
                    if ($CatItemUnitBusiness != null)
                        $query->where('item_movement_rel_extra.item_unit_business_id', $CatItemUnitBusiness);
                    if ($CatItemMoldCavity != null)
                        $query->where('item_movement_rel_extra.item_mold_cavities_id', $CatItemMoldCavity);


                    if ($CatItemPackageMeasurement != null)
                        $query->where('item_movement_rel_extra.item_package_measurements_id', $CatItemPackageMeasurement);
                    if ($CatItemUnitsPerPackage != null)
                        $query->where('item_movement_rel_extra.item_units_per_package_id', $CatItemUnitsPerPackage);
                    if ($CatItemMoldProperty != null)
                        $query->where('item_movement_rel_extra.item_mold_properties_id', $CatItemMoldProperty);
                    if ($CatItemProductFamily != null)
                        $query->where('item_movement_rel_extra.item_product_family_id', $CatItemProductFamily);
                    if ($color != null)
                        $query->where('item_movement_rel_extra.item_color_id', $color);


                    if ($CatItemSize != null)
                        $query->where('item_movement_rel_extra.item_size_id', $CatItemSize);
                }
            }
        }

        /**
         * Devuelve el total de stock para cada categoria basado en la tabla item_movement
         * Si $establisnment_id es diferente a 0, se sumaria basado en el establecimiento. Solo se suma los elementos
         * countable
         *
         * @param int $item_id
         * @param int $establisnment_id
         *
         * @return array
         */
        public static function getStockByCategory($item_id = 0, $establisnment_id = 0)
        {

            $data = [];
            $data['total'] = (float)self::getQueryToStock($item_id, $establisnment_id)->select(DB::raw(' sum(item_movement.quantity) as total'))->first()->total;

            self::getFormatedStockData($data, 'CatItemStatus', 'item_status_id', $item_id, $establisnment_id);
            self::getFormatedStockData($data, 'CatItemUnitBusiness', 'item_unit_business_id', $item_id, $establisnment_id);
            self::getFormatedStockData($data, 'CatItemMoldCavity', 'item_mold_cavities_id', $item_id, $establisnment_id);
            self::getFormatedStockData($data, 'CatItemPackageMeasurement', 'item_package_measurements_id', $item_id, $establisnment_id);
            self::getFormatedStockData($data, 'CatItemUnitsPerPackage', 'item_units_per_package_id', $item_id, $establisnment_id);
            self::getFormatedStockData($data, 'CatItemMoldProperty', 'item_mold_properties_id', $item_id, $establisnment_id);
            self::getFormatedStockData($data, 'CatItemProductFamily', 'item_product_family_id', $item_id, $establisnment_id);
            self::getFormatedStockData($data, 'colors', 'item_color_id', $item_id, $establisnment_id);
            self::getFormatedStockData($data, 'CatItemSize', 'item_size_id', $item_id, $establisnment_id);


            if ($data['total'] == 0) $data['total'] = null;
            return $data;

        }

        /**
         * @param int $item_id
         * @param int $establisnment_id
         *
         * @return \Illuminate\Database\Query\Builder
         */
        protected static function getQueryToStock($item_id = 0, $establisnment_id = 0)
        {
            $query = DB::connection('tenant')
                ->table('item_movement')
                ->where('item_movement.countable', 1)
                ->where('item_movement.item_id', $item_id);
            if ($establisnment_id != 0) {
                $query->where('item_movement.establishment_id', $establisnment_id);
            }
            $query->join('item_movement_rel_extra', 'item_movement_rel_extra.item_movement_id', '=', 'item_movement.id');

            return $query;
        }
        /**
         * @param int $item_id
         * @param int $establisnment_id
         *
         * @return \Illuminate\Database\Query\Builder
         */
        protected static function getQueryToStockWithOutItemId($establisnment_id = 0)
        {
            $query = DB::connection('tenant')
                ->table('item_movement')
                ->where('item_movement.countable', 1)
            //     ->where('item_movement.item_id', $item_id)
            ;
            if ($establisnment_id != 0) {
                $query->where('item_movement.establishment_id', $establisnment_id);
            }
            $query->join('item_movement_rel_extra', 'item_movement_rel_extra.item_movement_id', '=', 'item_movement.id');

            return $query;
        }

        protected static function getFormatedStockData(&$array, $index, $columna, $item_id, $establisnment_id)
        {

            $tableNames = null;
            if ($index == 'CatItemStatus') {
                $tableNames = 'cat_item_status';
            } elseif ($index == 'CatItemUnitBusiness') {
                $tableNames = 'cat_item_unit_business';
            } elseif ($index == 'CatItemMoldCavity') {
                $tableNames = 'cat_item_mold_cavities';
            } elseif ($index == 'CatItemPackageMeasurement') {
                $tableNames = 'cat_item_package_measurements';
            } elseif ($index == 'CatItemUnitsPerPackage') {
                $tableNames = 'cat_item_units_per_package';
            } elseif ($index == 'CatItemMoldProperty') {
                $tableNames = 'cat_item_mold_properties';
            } elseif ($index == 'CatItemMoldProperty') {
                $tableNames = 'cat_item_product_family';
            } elseif ($index == 'CatItemMoldProperty') {
                $tableNames = 'cat_item_size';
            } elseif ($index == 'colors') {
                $tableNames = 'cat_colors_items';
            }


            $temp = self::getQueryToStock($item_id, $establisnment_id)->where('item_movement_rel_extra.' . $columna, '!=', 0)->select(DB::raw(' sum(item_movement.quantity) as total'));
            $total = (float)$temp->first()->total;

            $temp = self::getQueryToStock($item_id, $establisnment_id)->where('item_movement_rel_extra.' . $columna, '!=', 0);
            $select = 'item_movement_rel_extra.' . $columna . ' as rel_id, sum(item_movement.quantity) as total';
            $group = ['item_movement_rel_extra.' . $columna];
            if ($tableNames != null) {
                $temp->join($tableNames, 'item_movement_rel_extra.' . $columna, '=', $tableNames . '.id');
                $select = $tableNames . '.name, item_movement_rel_extra.' . $columna . ' as rel_id, sum(item_movement.quantity) as total';
                // $group = $tableNames.'.name, item_movement_rel_extra.' . $columna;
                $group[]= $tableNames.'.name';
            }
            $temp->select(DB::raw($select));
            $temp->groupBy($group);

            $array[$index]['detailed'] = $temp->get();
            if ($total == 0) {
                $array[$index]['total'] = null;
            } else {
                $array[$index]['total'] = (float)$total;
            }
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
        public function contract_item()
        {
            return $this->belongsTo(ContractItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function devolution_item()
        {
            return $this->belongsTo(DevolutionItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function dispatch_item()
        {
            return $this->belongsTo(DispatchItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function document_item()
        {
            return $this->belongsTo(DocumentItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function expense_item()
        {
            return $this->belongsTo(ExpenseItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function fixed_asset_item()
        {
            return $this->belongsTo(FixedAssetItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function fixed_asset_purchase_item()
        {
            return $this->belongsTo(FixedAssetPurchaseItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function order_form_item()
        {
            return $this->belongsTo(OrderFormItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function order_note_item()
        {
            return $this->belongsTo(OrderNoteItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function purchase_item()
        {
            return $this->belongsTo(PurchaseItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function purchase_order_item()
        {
            return $this->belongsTo(PurchaseOrderItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function purchase_quotation_item()
        {
            return $this->belongsTo(PurchaseQuotationItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function quotation_item()
        {
            return $this->belongsTo(QuotationItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function sale_note_item()
        {
            return $this->belongsTo(SaleNoteItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function sale_opportunity_item()
        {
            return $this->belongsTo(SaleOpportunityItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function technical_service_item()
        {
            return $this->belongsTo(TechnicalServiceItem::class);
        }

        /**
         * @return BelongsTo
         */
        public function item()
        {
            return $this->belongsTo(Item::class);
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
         * @return ItemMovement
         */
        public function setItemId(?int $item_id): ItemMovement
        {
            $this->item_id = $item_id;
            return $this;
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
         * @return ItemMovement
         */
        public function setQuantity(?float $quantity): ItemMovement
        {
            $this->quantity = $quantity;
            return $this;
        }

        /**
         * @return string|null
         */
        public function getDateOfMovement(): ?string
        {
            return $this->date_of_movement;
        }

        /**
         * @param string|null $date_of_movement
         *
         * @return ItemMovement
         */
        public function setDateOfMovement(?string $date_of_movement): ItemMovement
        {
            $this->date_of_movement = $date_of_movement;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getContractItemId(): ?int
        {
            return $this->contract_item_id;
        }

        /**
         * @param int|null $contract_item_id
         *
         * @return ItemMovement
         */
        public function setContractItemId(?int $contract_item_id): ItemMovement
        {
            $this->contract_item_id = $contract_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getDevolutionItemId(): ?int
        {
            return $this->devolution_item_id;
        }

        /**
         * @param int|null $devolution_item_id
         *
         * @return ItemMovement
         */
        public function setDevolutionItemId(?int $devolution_item_id): ItemMovement
        {
            $this->devolution_item_id = $devolution_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getDispatchItemId(): ?int
        {
            return $this->dispatch_item_id;
        }

        /**
         * @param int|null $dispatch_item_id
         *
         * @return ItemMovement
         */
        public function setDispatchItemId(?int $dispatch_item_id): ItemMovement
        {
            $this->dispatch_item_id = $dispatch_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getDocumentItemId(): ?int
        {
            return $this->document_item_id;
        }

        /**
         * @param int|null $document_item_id
         *
         * @return ItemMovement
         */
        public function setDocumentItemId(?int $document_item_id): ItemMovement
        {
            $this->document_item_id = $document_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getExpenseItemId(): ?int
        {
            return $this->expense_item_id;
        }

        /**
         * @param int|null $expense_item_id
         *
         * @return ItemMovement
         */
        public function setExpenseItemId(?int $expense_item_id): ItemMovement
        {
            $this->expense_item_id = $expense_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getFixedAssetItemId(): ?int
        {
            return $this->fixed_asset_item_id;
        }

        /**
         * @param int|null $fixed_asset_item_id
         *
         * @return ItemMovement
         */
        public function setFixedAssetItemId(?int $fixed_asset_item_id): ItemMovement
        {
            $this->fixed_asset_item_id = $fixed_asset_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getFixedAssetPurchaseItemId(): ?int
        {
            return $this->fixed_asset_purchase_item_id;
        }

        /**
         * @param int|null $fixed_asset_purchase_item_id
         *
         * @return ItemMovement
         */
        public function setFixedAssetPurchaseItemId(?int $fixed_asset_purchase_item_id): ItemMovement
        {
            $this->fixed_asset_purchase_item_id = $fixed_asset_purchase_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getOrderFormItemId(): ?int
        {
            return $this->order_form_item_id;
        }

        /**
         * @param int|null $order_form_item_id
         *
         * @return ItemMovement
         */
        public function setOrderFormItemId(?int $order_form_item_id): ItemMovement
        {
            $this->order_form_item_id = $order_form_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getOrderNoteItemId(): ?int
        {
            return $this->order_note_item_id;
        }

        /**
         * @param int|null $order_note_item_id
         *
         * @return ItemMovement
         */
        public function setOrderNoteItemId(?int $order_note_item_id): ItemMovement
        {
            $this->order_note_item_id = $order_note_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getPurchaseItemId(): ?int
        {
            return $this->purchase_item_id;
        }

        /**
         * @param int|null $purchase_item_id
         *
         * @return ItemMovement
         */
        public function setPurchaseItemId(?int $purchase_item_id): ItemMovement
        {
            $this->purchase_item_id = $purchase_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getPurchaseOrderItemId(): ?int
        {
            return $this->purchase_order_item_id;
        }

        /**
         * @param int|null $purchase_order_item_id
         *
         * @return ItemMovement
         */
        public function setPurchaseOrderItemId(?int $purchase_order_item_id): ItemMovement
        {
            $this->purchase_order_item_id = $purchase_order_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getPurchaseQuotationItemId(): ?int
        {
            return $this->purchase_quotation_item_id;
        }

        /**
         * @param int|null $purchase_quotation_item_id
         *
         * @return ItemMovement
         */
        public function setPurchaseQuotationItemId(?int $purchase_quotation_item_id): ItemMovement
        {
            $this->purchase_quotation_item_id = $purchase_quotation_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getQuotationItemId(): ?int
        {
            return $this->quotation_item_id;
        }

        /**
         * @param int|null $quotation_item_id
         *
         * @return ItemMovement
         */
        public function setQuotationItemId(?int $quotation_item_id): ItemMovement
        {
            $this->quotation_item_id = $quotation_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getSaleNoteItemId(): ?int
        {
            return $this->sale_note_item_id;
        }

        /**
         * @param int|null $sale_note_item_id
         *
         * @return ItemMovement
         */
        public function setSaleNoteItemId(?int $sale_note_item_id): ItemMovement
        {
            $this->sale_note_item_id = $sale_note_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getSaleOpportunityItemId(): ?int
        {
            return $this->sale_opportunity_item_id;
        }

        /**
         * @param int|null $sale_opportunity_item_id
         *
         * @return ItemMovement
         */
        public function setSaleOpportunityItemId(?int $sale_opportunity_item_id): ItemMovement
        {
            $this->sale_opportunity_item_id = $sale_opportunity_item_id;
            return $this;
        }

        /**
         * @return int|null
         */
        public function getTechnicalServiceItemId(): ?int
        {
            return $this->technical_service_item_id;
        }

        /**
         * @param int|null $technical_service_item_id
         *
         * @return ItemMovement
         */
        public function setTechnicalServiceItemId(?int $technical_service_item_id): ItemMovement
        {
            $this->technical_service_item_id = $technical_service_item_id;
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
         * @return ItemMovement
         */
        public function setEstablishmentId(?int $establishment_id): ItemMovement
        {
            $this->establishment_id = $establishment_id;
            return $this;
        }

        /**
         * @return bool
         */
        public function isCountable(): bool
        {
            return $this->countable;
        }

        /**
         * @param bool $countable
         *
         * @return ItemMovement
         */
        public function setCountable(bool $countable): ItemMovement
        {
            $this->countable = $countable;
            return $this;
        }

        /**
         * @return Devolution|Contract|Dispatch|Document|Expense|FixedAssetPurchase|OrderForm|OrderNote|Purchase|PurchaseOrder|PurchaseQuotation|Quotation|SaleNote| SaleOpportunity| TechnicalService|null
         *
         *                                     */
        public function getParentModel()
        {
            try {
                if ($this->devolution_item_id != 0) {
                    // return $this->devolution_item->devolution;
                } elseif ($this->dispatch_item_id != 0) {
                    // return $this->dispatch_item->dispatch;
                } elseif ($this->document_item_id != 0) {
                    return $this->document_item->document;
                } elseif ($this->order_form_item_id != 0) {
                    // return $this->order_form_item->order_form;
                } elseif ($this->order_note_item_id != 0) {
                    // return $this->order_note_item->order_note;
                } elseif ($this->purchase_item_id != 0) {

                    return $this->purchase_item->purchase;
                } elseif ($this->purchase_order_item_id != 0) {
                    // return $this->purchase_order_item->purchase;
                } elseif ($this->purchase_quotation_item_id != 0) {
                    // return $this->purchase_quotation_item->purchase_quotation;
                } elseif ($this->quotation_item_id != 0) {
                    // return $this->quotation_item->quotation;
                } elseif ($this->sale_note_item_id != 0) {
                    return $this->sale_note_item->sale_note;
                } elseif ($this->sale_opportunity_item_id != 0) {
                    // return $this->sale_opportunity_item->sale_opportunity;
                } elseif ($this->technical_service_item_id != 0) {
                    return $this->technical_service_item->technical_service;
                } elseif ($this->fixed_asset_purchase_item_id != 0) {
                    // return $this->fixed_asset_purchase_item->fixed_asset_purchase;
                }
            } catch (ErrorException $e) {
                // do nothing
                $this->delete();
            }
            return null;

        }

        /**
         * @param Builder $query
         *
         * @return Builder
         */
        public function scopeOnlyCountable($query)
        {
            return $query->where('countable', 1);
        }

        /**
         * @param Builder $query
         * @param int     $item
         *
         * @return Builder
         */
        public function scopeByItem($query, $item = 0)
        {
            return $query->where('item_id', $item);
        }

        /**
         * @return HasOne
         */
        public function item_movement_rel_extra()
        {
            return $this->hasOne(ItemMovementRelExtra::class);
        }
    }
