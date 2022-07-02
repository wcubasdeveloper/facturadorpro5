<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\BusinessTurn\Models\DocumentHotel;

class DownloadTrayCollecion extends ResourceCollection
{

    public function toArray($request) {

        return $this->collection->transform(function($row, $key){

            return [
                'id' => $row->id,
                'user_id' => $row->user_id,
                'module' => $row->module,
                'format' => $row->format,
                'file_name' => $row->file_name,
                'status' => $row->status,
                'date_init' => $row->date_init,
                'date_end' => $row->date_end,
                'user' => $row->user->name,
                'type' => $row->type,

            ];
        });
    }
}
