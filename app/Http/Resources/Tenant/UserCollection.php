<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class UserCollection
 *
 * @package App\Http\Resources\Tenant
 * @mixin  ResourceCollection
 */
class UserCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function toArray($request = null)
    {
        return  $this->collection->transform(function($row, $key) {
            /** @var \App\Models\Tenant\User  $row */
            $type = '';
            switch ($row->type) {
                case 'admin':
                    $type =  'Administrador' ;
                    break;
                case 'seller':
                    $type =  'Vendedor' ;
                        break;
                case 'client':
                    $type =  'Cliente' ;
                    break;
                default:
                    # code...
                    break;
            }

            return [
                'id' => $row->id,
                'email' => $row->email,
                'name' => $row->name,
                'api_token' => $row->api_token,
                'document_id' => $row->document_id,
                'serie_id' => ($row->series_id == 0)?null:$row->series_id,
                'establishment_description' => optional($row->establishment)->description,
                'type' => $type,
                'locked' => (bool) $row->locked,

            ];
        })->sortBy('id');
    }
}
