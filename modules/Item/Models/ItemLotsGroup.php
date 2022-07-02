<?php

namespace Modules\Item\Models;

use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;


/**
 * Modules\Item\Models\ItemLotsGroup
 *
 * @property Item $item
 * @method static \Illuminate\Database\Eloquent\Builder|ItemLotsGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemLotsGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemLotsGroup query()
 * @mixin \Eloquent
 */
class ItemLotsGroup extends ModelTenant
{
    protected $table = 'item_lots_group';


    protected $fillable = [
        'code',
        'quantity',
        'date_of_due',
        'item_id',
        'old_quantity'
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     *
     * @return ItemLotsGroup
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param mixed $quantity
     *
     * @return ItemLotsGroup
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDateOfDue()
    {
        return $this->date_of_due;
    }

    /**
     * @param mixed $date_of_due
     *
     * @return ItemLotsGroup
     */
    public function setDateOfDue($date_of_due)
    {
        $this->date_of_due = $date_of_due;
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
     * @return ItemLotsGroup
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;
        return $this;
    }

}
