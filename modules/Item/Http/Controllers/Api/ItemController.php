<?php

namespace Modules\Item\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Item;
use Modules\Item\Http\Requests\Api\ItemRequest;


class ItemController extends Controller
{
    
    /**
     * 
     * Actualizar item
     *
     * @param  ItemRequest $request
     * @return array
     */
    public function update(ItemRequest $request)
    {

        $item = Item::findOrFail($request->id);
        $item->fill($request->all());
        $item->update();

        return [
            'success' => true,
            'message' => 'Producto actualizado',
            'data' => [
                'id' => $item->id,
                'internal_id' => $item->internal_id,
                'item_code' => $item->item_code,
                'description' => $item->description,
                'name' => $item->name,
                'second_name' => $item->second_name,
                'unit_type_id' => $item->unit_type_id,
                'currency_type_id' => $item->currency_type_id,
                'sale_unit_price' => $item->sale_unit_price,
                'purchase_unit_price' => $item->purchase_unit_price,
                'has_isc' => $item->has_isc,
                'system_isc_type_id' => $item->system_isc_type_id,
                'percentage_isc' => $item->percentage_isc,
                'sale_affectation_igv_type_id' => $item->sale_affectation_igv_type_id,
                'purchase_affectation_igv_type_id' => $item->purchase_affectation_igv_type_id,
                'calculate_quantity' => $item->calculate_quantity,
                'has_igv' => $item->has_igv,
                'has_perception' => $item->has_perception,
                'percentage_of_profit' => $item->percentage_of_profit,
                'percentage_perception' => $item->percentage_perception,
                'category_id' => $item->category_id,
                'brand_id' => $item->brand_id,
                'barcode' => $item->barcode,
            ]
        ];
    }

}
