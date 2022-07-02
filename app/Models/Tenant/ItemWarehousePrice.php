<?php

namespace App\Models\Tenant;

// use Hyn\Tenancy\Abstracts\TenantModel;
use Modules\Inventory\Models\ItemWarehouse;

class ItemWarehousePrice extends ModelTenant
{
    protected $table = 'item_warehouse_prices';

    protected $fillable = [
        'item_id',
        'warehouse_id',
        'price',
    ];

    protected $casts = [
        'price' => 'float',
        'warehouse_id' => 'int',
        'item_id' => 'int',
    ];

    public $timestamps = false;


    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return mixed
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param mixed $item_id
     *
     * @return ItemWarehousePrice
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWarehouseId()
    {
        return $this->warehouse_id;
    }

    /**
     * @param mixed $warehouse_id
     *
     * @return ItemWarehousePrice
     */
    public function setWarehouseId($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Devuelve la descripcion del almacen
     * @return string|null
     */
    public function getWarehouseDescription(){
        if(empty( $this->warehouse)) {
            return null;
        }
        return $this->warehouse->description;
    }
    /**
     * @param float $price
     *
     * @return ItemWarehousePrice
     */
    public function setPrice($price)
    {
        $this->price = (float) $price;
        return $this;
    }

}
