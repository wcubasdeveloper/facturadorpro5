<?php

    namespace Modules\Inventory\Models;

    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use Modules\Inventory\Models\InventoryKardex;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;
    use Modules\Item\Models\ItemLot;


    /**
     * Class Inventory
     *
     * @property int                                                             $id

     * @property string|null                                                     $type
     * @property string                                                          $description
     * @property string|null                                                     $detail
     * @property int                                                             $item_id
     * @property int                                                             $warehouse_id
     * @property int|null                                                        $warehouse_destination_id
     * @property string|null                                                     $inventory_transaction_id
     * @property float                                                           $quantity
     * @property string|null                                                     $lot_code
     * @property int|null                                                        $inventories_transfer_id
     * @property Carbon|null                                                     $created_at
     * @property Carbon|null                                                     $updated_at
     * @property \Modules\Inventory\Models\InventoryTransfer|null                $inventories_transfer
     * @property InventoryTransaction|null                                       $inventory_transaction
     * @property Item                                                            $item
     * @property Warehouse                                                       $warehouse
     * @property-read \Illuminate\Database\Eloquent\Collection|InventoryKardex[] $inventory_kardex
     * @package Modules\Inventory\Models
     * @mixin ModelTenant
     * @property-read int|null                                                   $inventory_kardex_count
     * @property-read \Illuminate\Database\Eloquent\Collection|ItemLot[]         $lots
     * @property-read int|null                                                   $lots_count
     * @property-read \Modules\Inventory\Models\InventoryTransaction             $transaction
     * @property-read \Modules\Inventory\Models\Warehouse                        $warehouse_destination
     * @mixin \Eloquent
     * @method static Builder|Inventory newModelQuery()
     * @method static Builder|Inventory newQuery()
     * @method static Builder|Inventory query()
     */
    class Inventory extends ModelTenant
    {
        use UsesTenantConnection;

        protected $with = [
            'transaction',
            'warehouse',
            'warehouse_destination',
            'item'
        ];

        protected $casts = [
            'item_id' => 'int',

            'warehouse_id' => 'int',
            'warehouse_destination_id' => 'int',
            'quantity' => 'float',
            'inventories_transfer_id' => 'int'
        ];
        protected $fillable = [
            'type',
            'description',
            'item_id',
            'warehouse_id',

            'warehouse_destination_id',
            'quantity',
            'inventory_transaction_id',
            'lot_code',
            'detail',
            'inventories_transfer_id',
            'comments',
            'created_at'
        ];

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function warehouse()
        {
            return $this->belongsTo(Warehouse::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function warehouse_destination()
        {
            return $this->belongsTo(Warehouse::class, 'warehouse_destination_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function item()
        {
            return $this->belongsTo(Item::class, 'item_id');
        }

        /**
         * Se usa en la relacion con el inventario kardex en modules/Inventory/Traits/InventoryTrait.php.
         * Tambien se debe tener en cuenta modules/Inventory/Providers/InventoryKardexServiceProvider.php y
         * app/Providers/KardexServiceProvider.php para la correcta gestion de kardex
         *
         * @return \Illuminate\Database\Eloquent\Relations\MorphMany
         */
        public function inventory_kardex()
        {
            return $this->morphMany(InventoryKardex::class, 'inventory_kardexable');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function transaction()
        {
        return $this->belongsTo(InventoryTransaction::class, 'inventory_transaction_id');
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\MorphMany
         */
        public function lots()
        {
            return $this->morphMany(ItemLot::class, 'item_loteable');
        }
    
    /**
     * Obtener datos para reporte movimientos
     *
     * @return array
     */
    public function getRowResourceReport()
    {

        $input = '-';
        $output = '-';

        if($this->transaction->type === 'input'){
            $input = $this->quantity;
        }else{
            $output = -$this->quantity;
        }

        return [
            'description' => $this->description,
            'item_id' => $this->item_id,
            'item_description' => $this->item->getInternalIdDescription(),
            'inventory_transaction_id' => $this->inventory_transaction_id,
            'quantity' => $this->quantity,
            'input' => $input,
            'output' => $output,
            'date_time' => $this->created_at->format('Y-m-d H:i:s'),
        ];

    }

    /**
     * Filtros para reporte movimientos
     * Usado en:
     * ReportMovementController
     *
     * @param  $query
     * @param  $warehouse_id
     * @param  $inventory_transaction_id
     * @param  $date_start
     * @param  $date_end
     * @param  $order_inventory_transaction_id
     */
    public function scopeWhereFilterReportMovement($query, $warehouse_id, $inventory_transaction_id, $date_start, $date_end, $item_id, $order_inventory_transaction_id)
    {

        $_order_inventory_transaction_id = $order_inventory_transaction_id == 'true';

        $query->with(['inventory_kardex'])
                    ->whereHas('transaction')
                    ->where('warehouse_id', $warehouse_id)
                    ->whereHas('inventory_kardex', function($query) use($date_start, $date_end, $item_id){

                        if ($date_start) $query->where('date_of_issue', '>=', $date_start);
                        if ($date_end) $query->where('date_of_issue', '<=', $date_end);
                        if ($item_id) $query->where('item_id', $item_id);

                    });

        if($inventory_transaction_id) $query->where('inventory_transaction_id', $inventory_transaction_id);

        if($_order_inventory_transaction_id) $query->orderBy('inventory_transaction_id');

        return $query;
    }



        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function inventories_transfer()
        {
            return $this->belongsTo(InventoryTransfer::class);
        }

        /**
         * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
         */
        public function inventory_transaction()
        {
            return $this->belongsTo(InventoryTransaction::class, 'inventory_transaction_id', 'id');
        }

    }
