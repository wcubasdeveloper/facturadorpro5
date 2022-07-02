<?php

    namespace Modules\Inventory\Models;

    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Eloquent;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Builder;

    /**
     * \Modules\Inventory\Models\ItemWarehouse
     *
     * @property int         $id
     * @property int         $item_id
     * @property int         $warehouse_id
     * @property float       $stock
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @property Item        $item
     * @property Warehouse   $warehouse
     * @method static Builder|ItemWarehouse newModelQuery()
     * @method static Builder|ItemWarehouse newQuery()
     * @method static Builder|ItemWarehouse query()
     * @method static Builder|ItemWarehouse whereWarehouse($warehouse_id)
     * @mixin ModelTenant
     * @mixin Eloquent
     */
    class ItemWarehouse extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'item_warehouse';

        protected $fillable = [
            'item_id',
            'warehouse_id',
            'stock',
        ];

        protected $casts = [
            'stock' => 'float',
            'item_id' => 'int',
            'warehouse_id' => 'int',
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
        public function item()
        {
            return $this->belongsTo(Item::class);
        }


        /**
         * @param Builder $query
         * @param         $warehouse_id
         *
         * @return Builder
         */
        public function scopeWhereWarehouse(\Illuminate\Database\Eloquent\Builder $query, $warehouse_id)
        {
            if (!is_null($warehouse_id) && $warehouse_id !== 'all') {
                return $query->where('warehouse_id', $warehouse_id);
            }
            return $query;
        }

        
        /**
         * 
         * Filtrar por categoria del item - reporte inventario
         * 
         * @param Builder $query
         * @param int $category_id
         *
         * @return Builder
         */
        public function scopeWhereItemCategory(Builder $query, $category_id)
        {
            return $query->whereHas('item', function ($q) use($category_id){
                $q->where('category_id', $category_id);
            });
        }

        
        /**
         * 
         * Filtrar por marca del item - reporte inventario
         * 
         * @param Builder $query
         * @param int $brand_id
         *
         * @return Builder
         */
        public function scopeWhereItemBrand(Builder $query, $brand_id)
        {
            return $query->whereHas('item', function ($q) use($brand_id){
                $q->where('brand_id', $brand_id);
            });
        }


//    public function scopeWhereFilter($query, $filter, $stock_min)
//    {
//        if($filter === '02') {
//            return $query->where('stock', '<', 0);
//        }
//        if($filter === '03') {
//            return $query->where('stock', '=', 0);
//        }
//        if($filter === '04') {
//            return $query->where('stock', '>', 0)
//                         ->where('stock', '<=', $stock_min);
//        }
//        if($filter === '05') {
//            return $query->where('stock', '>', $stock_min);
//        }
//
//        return $query;
//    }
    }
