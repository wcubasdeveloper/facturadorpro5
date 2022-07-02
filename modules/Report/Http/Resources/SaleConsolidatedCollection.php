<?php

namespace Modules\Report\Http\Resources;

use App\Models\Tenant\Item;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SaleConsolidatedCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return $this->collection->transform(function($row, $key){
            $unit_type_id = $row->relation_item->unit_type_id;
            $unit_price = 1;
            $category  = [
                'id' => '',
                'name' => ''
            ];
            $brand = [
                'id' => '',
                'name' => ''
            ];
            if($row->item !== null ) {
                try {
                    if (
                        property_exists($row->item, 'presentation') &&
                        $row->item->presentation !== null &&
                        is_object($row->item->presentation) &&
                        property_exists($row->item->presentation, 'unit_type_id')
                    ) {
                        $unit_type_id = $row->item->presentation->unit_type_id;
                    }
                } catch (Exception $e) {
                    $unit_type_id = $row->relation_item->unit_type_id;

                }
                // unit_price
                if($unit_type_id !== 'ZZ'){
                    $item = Item::select('brand_id')->where('internal_id',$row->item->internal_id)->first();
                    if(!empty($item)){
                        $brand = $item->brand;
                        $category = $item->category;
                    }
                }
                // category_id
                if (property_exists($row->item,'unit_price')) {
                    $unit_price = $row->item->unit_price;
                }

            }

            return [
                'id' => $row->id,
                'item_internal_id' => $row->relation_item->internal_id,
                'item_unit_type_id' => $unit_type_id,
                // 'item_unit_type_id' => $row->relation_item->unit_type_id,
                'brand' => $brand,
                'category' => $category,
                'item_description' => $row->item->description,
                'item_quantity' => $row->quantity,
                'total_sale' => $unit_price * $row->quantity,
                'series' => $row->series ?? 'NV',
                'number' => $row->number ?? $row->id,
                'date_of_issue' => $row->date_of_issue,
            ];
        });
    }

}
