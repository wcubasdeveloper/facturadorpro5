<?php

namespace Modules\MercadoPago\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientErrorCollection extends ResourceCollection
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
            return [
                'id' => $row->id,
                'code' => $row->code,
                'client_error_type_id' => $row->client_error_type_id,
                'original_message' => $row->original_message,
                'user_message' => $row->user_message,
                'client_error_type_name' => $row->client_error_type->name
            ];
        });
    }
}