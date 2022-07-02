<?php

namespace App\Models\Tenant;

use App\Traits\AttributePerItems;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class DispatchItem
 *
 * @package App\Models\Tenant
 * @mixin ModelTenant
 * @property \App\Models\Tenant\Dispatch $dispatch
 * @property mixed $item
 * property \App\Models\Tenant\Item $relation_item
 * @method static Builder|DispatchItem joinDispatch()
 * @method static Builder|DispatchItem joinItem()
 * @method static Builder|DispatchItem newModelQuery()
 * @method static Builder|DispatchItem newQuery()
 * @method static Builder|DispatchItem query()
 */
class DispatchItem extends ModelTenant
{
    use AttributePerItems;
    public $timestamps = false;
    protected $with = [
        'relation_item',
    ];
    protected $fillable = [
        'dispatch_id',
        'item_id',
        'item',
        'quantity',
        'name_product_pdf',
    ];

    /**
     * @param int $decimal
     *
     * @return string
     */
    public function getQtyFormated($decimal = 2){
        return number_format($this->quantity,$decimal);
    }

    public function getItemAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value))?null:json_encode($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function relation_item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJoinDispatch(Builder $query){
        $query->join('dispatches','dispatches.id','=','dispatch_items.dispatch_id');

        return $query;
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJoinItem(Builder $query){
        $query->join('items','items.id','=','dispatch_items.item_id');

        return $query;
    }

    /**
     * Retorna un standar de nomenclatura para el modelo
     *
     * @param \App\Models\Tenant\Configuration|null $configuration
     *
     * @return array
     */
    public function getCollectionData(Configuration $configuration = null) {
        $dispatches = Dispatch::find($this->dispatch_id);
        $item = Item::find($this->item_id);
        if (null === $configuration) {
            $configuration = Configuration::first();
        }

        $this->quantity = number_format($this->quantity,2);
        $data = $this->toArray();
        $data['item'] = [];
        $data['dispatches'] = [];
        if (!empty($dispatches)) {
            $data['dispatches'] = $dispatches->getCollectionData();
        }
        if (!empty($item)) {
            $data['item'] = $item->getCollectionData($configuration);
        }

        return $data;
    }

}
