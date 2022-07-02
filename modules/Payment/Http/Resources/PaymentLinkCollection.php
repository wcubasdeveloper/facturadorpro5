<?php

namespace Modules\Payment\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;


class PaymentLinkCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            return $row->getRowCollection();
        });
    }

}
