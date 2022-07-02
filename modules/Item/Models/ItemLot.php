<?php

namespace Modules\Item\Models;

use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;
use Modules\Inventory\Models\Warehouse;

/**
 * Modules\Item\Models\ItemLot
 *
 * @property Item $item
 * @property \Illuminate\Database\Eloquent\Model|\Eloquent $item_loteable
 * @property Warehouse $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder|ItemLot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemLot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemLot query()
 * @mixin \Eloquent
 */
class ItemLot extends ModelTenant
{

    protected $fillable = [
        'series',
        'date',
        'item_id',
        'warehouse_id',
        'item_loteable_type',
        'item_loteable_id',
        'has_sale',
        'state'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function item_loteable()
    {
        return $this->morphTo();
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * @return mixed
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * @param mixed $series
     *
     * @return ItemLot
     */
    public function setSeries($series)
    {
        $this->series = $series;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     *
     * @return ItemLot
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
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
     * @return ItemLot
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
     * @return ItemLot
     */
    public function setWarehouseId($warehouse_id)
    {
        $this->warehouse_id = $warehouse_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItemLoteableType()
    {
        return $this->item_loteable_type;
    }

    /**
     * @param mixed $item_loteable_type
     *
     * @return ItemLot
     */
    public function setItemLoteableType($item_loteable_type)
    {
        $this->item_loteable_type = $item_loteable_type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getItemLoteableId()
    {
        return $this->item_loteable_id;
    }

    /**
     * @param mixed $item_loteable_id
     *
     * @return ItemLot
     */
    public function setItemLoteableId($item_loteable_id)
    {
        $this->item_loteable_id = $item_loteable_id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHasSale()
    {
        return $this->has_sale;
    }

    /**
     * @param bool $has_sale
     *
     * @return ItemLot
     */
    public function setHasSale($has_sale)
    {
        $this->has_sale = (bool) $has_sale;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     *
     * @return ItemLot
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    // public function scopeWhereLot($query)
    // {
    //     $establishment_id = auth()->user()->establishment_id;

    //     $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();

    //     return $query->where('has_sale', false)->whereHas('warehouse', function($q) use($warehouse){
    //             $q->where('warehouse_id', $warehouse->id);
    //     });

    // }

}
