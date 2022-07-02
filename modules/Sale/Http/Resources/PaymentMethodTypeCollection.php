<?php

namespace Modules\Sale\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PaymentMethodTypeCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {

        return $this->collection->transform(function($row, $key) {

            /** @var \App\Models\Tenant\PaymentMethodType  $row */
            $show_actions = true;

            if(in_array($row->id, ['01', '05', '08', '09'])){
                $show_actions = false;
            }
            $return = $row->toArray();
            $return['show_actions'] = $show_actions;
            return $return;

            return [
                'id' => $row->id,
                'description' => $row->description,
                'show_actions' => $show_actions
            ];
        });
    }
}
