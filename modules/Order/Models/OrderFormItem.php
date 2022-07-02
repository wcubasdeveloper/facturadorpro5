<?php

namespace Modules\Order\Models;

use App\Models\Tenant\Item;
use App\Models\Tenant\ModelTenant;
use App\Traits\AttributePerItems;

class OrderFormItem extends ModelTenant
{
    use AttributePerItems;
    public $timestamps = false;

    protected $fillable = [
        'order_form_id',
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


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order_form()
    {
        return $this->belongsTo(OrderForm::class,'order_form_id');
    }

    public function relation_item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    /**
     *  Devuelve la descripcion del producto
     *
     * @return string
     */
    public function getTemplateDescription()
    {
        return $this->item->description;

        // if (empty($this->name_product_pdf)) {
        //     return $this->item->description;
        // }
        // return $this->getNameProductPdf();
    }

    /**
     * @return string
     */
    public function getNameProductPdf(): string
    {
        // @todo Si existe name_product, añadirlo aqui
        return $this->item->description;

        return $this->name_product_pdf;
    }
}
