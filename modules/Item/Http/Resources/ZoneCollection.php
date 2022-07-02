<?php

namespace Modules\Item\Http\Resources;

use App\Models\Tenant\Zone;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ZoneCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function(Zone $row, $key) {

            return [
                'id' => $row->id,
                'name' => $row->name,
            ];
        });

    }

}
