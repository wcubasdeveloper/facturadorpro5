<?php

namespace App\Models\Tenant;


use Illuminate\Support\Facades\Config;

class ItemWarehouse extends ModelTenant
{
    protected $table = 'item_warehouse';

    protected $fillable = [
        'item_id',
        'warehouse_id',
        'stock',
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
     * Devuelve la descripcion del almacen
     *
     * @return string
     */
    public function getWarehouseDescription(){
        /** @var Warehouse $warehouse */
        $warehouse = $this->warehouse()->first();
        if(!empty($warehouse)){
            return $warehouse->description;
        }
        return '';
    }

    /**
     * Suma una cantidad $qty al stock. Si la cantidad es negativa lo restará
     *
     * @param float|int $qty
     * @return $this
     */
    public function addStock (float $qty =0 ){
        $this->stock += $qty;
        return $this;
    }
}
