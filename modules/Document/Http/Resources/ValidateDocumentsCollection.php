<?php

namespace Modules\Document\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;
use App\Models\Tenant\StateType;


class ValidateDocumentsCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request) {

        $state_types = StateType::get();
        
        return $this->collection->transform(function($row, $key) use($state_types){
            
            if(is_null($row->sunat_state_type_id)){

                $sunat_state_type_description = 'Error en la busqueda: '.$row->message;

            }else{
                
                $state_type = $state_types->first(function($state) use($row){
                    return $state->id === $row->sunat_state_type_id;
                });

                $sunat_state_type_description = null;

                if($state_type){
                    $sunat_state_type_description = $state_type->description;
                }else{
                    $sunat_state_type_description = 'No existe';
                }

            }

            return [
                'id' => $row->id,
                'soap_type_id' => $row->soap_type_id,
                'soap_type_description' => $row->soap_type->description,
                'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                'number' => $row->number_full,
                'customer_name' => $row->customer->name,
                'customer_number' => $row->customer->number, 
                'total' => $row->total,
                'state_type_id' => $row->state_type_id,
                'state_type_description' => mb_strtoupper($row->state_type->description),
                'document_type_description' => $row->document_type->description,
                'document_type_id' => $row->document_type->id,  
                'message' => $row->message,
                'code' => $row->code,
                'sunat_state_type_description' => mb_strtoupper($sunat_state_type_description),
                'sunat_state_type_id' => $row->sunat_state_type_id,
                // 'response' => $row->response,
            ];
        });
    }
}
