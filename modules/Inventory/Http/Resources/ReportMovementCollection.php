<?php

namespace Modules\Inventory\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection; 


class ReportMovementCollection extends ResourceCollection
{

    public function toArray($request)
    {
        return $this->collection->transform(function($row, $key) {
            return  $row->getRowResourceReport();
        });
    }
  
}
