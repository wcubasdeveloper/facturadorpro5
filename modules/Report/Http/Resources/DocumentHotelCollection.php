<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\BusinessTurn\Models\DocumentHotel;

class DocumentHotelCollection extends ResourceCollection
{


    public function toArray($request) {


        return $this->collection->transform(function(DocumentHotel $row, $key){

            $document = $row->document;
            $items = $document->items;


            return [
                'id' => $row->id,
                'name' => $row->name,
                'number' => $row->number,
                'identity_document_type_description' => $row->identity_document_type->description,
                'sex' => $row->sex,
                'age' => $row->age,
                'civil_status' => $row->civil_status,
                'ocupation' => $row->ocupation,
                'nacionality' =>  $row->nacionality,
                'origin' =>  $row->origin,
                'room_number' => $row->room_number,
                'date_entry' => $row->date_entry,
                'time_entry' => $row->time_entry,
                'date_exit' => $row->date_exit,
                'time_exit' => $row->time_exit,
                'document' => $row->document->number_full,
                'items' => $items,


            ];
        });
    }
}
