<?php

namespace Modules\Inventory\Models;

use App\Models\Tenant\ModelTenant;

/**
 * Modules\Inventory\Models\DevolutionItem
 *
 * @property \Modules\Inventory\Models\Devolution $devolution
 * @property mixed $item
 * @method static \Illuminate\Database\Eloquent\Builder|DevolutionItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DevolutionItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DevolutionItem query()
 * @mixin \Eloquent
 */
class DevolutionItem extends ModelTenant
{

    public $timestamps = false;

    protected $fillable = [
        'devolution_id',
        'item_id',
        'item',
        'quantity',
    ];

    public function getItemAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setItemAttribute($value)
    {
        $this->attributes['item'] = (is_null($value))?null:json_encode($value);
    }

    public function devolution()
    {
        return $this->belongsTo(Devolution::class);
    }

}
