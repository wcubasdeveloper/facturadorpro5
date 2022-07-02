<?php

namespace Modules\Item\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;

class ItemHistorySalesCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request) {
        return $this->collection->transform(function($row, $key) {
            
            return [
                'id' => $row->id,
                'number_full' => "{$row->series}-{$row->number}",
                'series' => $row->series,
                'number' => $row->number,
                'date_of_issue' => $row->date_of_issue,
                'price' => $row->price, 
                'total' => $row->total, 
                'quantity' => $row->quantity, 
                'customer_name' => $row->customer_name, 
                'customer_number' => $row->customer_number, 
            ];
        });
    }
}
