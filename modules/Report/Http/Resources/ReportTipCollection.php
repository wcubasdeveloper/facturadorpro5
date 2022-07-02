<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ReportTipCollection extends ResourceCollection
{

    public function toArray($request) {

        return $this->collection->transform(function($row, $key){ 
            return $row->getRowResource();
        });
    }
}
