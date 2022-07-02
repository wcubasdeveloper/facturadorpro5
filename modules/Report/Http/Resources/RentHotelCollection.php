<?php

namespace Modules\Report\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Modules\BusinessTurn\Models\DocumentHotel;
use Modules\Hotel\Models\HotelRent;

class RentHotelCollection extends ResourceCollection
{


    public function toArray($request) {


        return $this->collection->transform(function(HotelRent $row, $key){
            $data = $row->toArray();
            $customer = $row->customer;
            $room = $row->room;
            $items = $row->products;
            $data['status'] = ucfirst(strtolower($data['status']));
            $data['customer'] = $customer;
            $data['room'] = $room;
            $data['items'] = $items;
            return  $data;

        });
    }
}
