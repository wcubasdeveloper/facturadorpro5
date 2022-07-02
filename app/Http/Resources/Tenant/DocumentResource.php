<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Document;
use App\Models\Tenant\Person;
use App\Models\Tenant\SaleNote;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {

        $response_message = null;
        $response_type = null;

        if($this->soap_shipping_response){
            if($this->soap_shipping_response->sent){

                $response_message = $this->soap_shipping_response->description;
                $code =  (int) $this->soap_shipping_response->code;

                if($code === 0) {
                    $response_type = 'success';
                }elseif($code < 2000) {
                    $response_type = 'error';
                }elseif ($code < 4000) {
                    $response_type = 'error';
                } else {
                    $response_type = 'warning';
                }
            }

        }else if ($this->regularize_shipping) {

            $response_message = "Por regularizar: {$this->response_regularize_shipping->code} - {$this->response_regularize_shipping->description}";
            $code =  (int) $this->response_regularize_shipping->code;
            $response_type = 'error';

        }

        /** @var Document $document */
        $document = $this->resource;

        $nvs = $document->getNvCollection();

        $customer = $document->customer;
        $customer_email = $customer->email;

        /** @var Person $person */
        $person = $document->person;
        $mails = $person->getCollectionData();
        $customer_email=  $mails['optional_email_send'];


        $data = [
            'id' => $document->id,
            'external_id' => $document->external_id,
            'group_id' => $document->group_id,
            'number' => $document->number_full,
            'regularize_shipping' => (bool) $document->regularize_shipping,
            'date_of_issue' => $document->date_of_issue->format('Y-m-d'),
            'customer_email' => $customer_email,
            'download_pdf' => $document->download_external_pdf,
            'print_ticket' => url('')."/print/document/{$document->external_id}/ticket",
            'print_a4' => url('')."/print/document/{$document->external_id}/a4",
            'print_a5' => url('')."/print/document/{$document->external_id}/a5",
            'image_detraction' => ($document->detraction) ? (($document->detraction->image_pay_constancy) ?
            asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'image_detractions'.DIRECTORY_SEPARATOR.$document->detraction->image_pay_constancy):false):false,
            'detraction' => $document->detraction,
            'response_message' => $response_message,
            'response_type' => $response_type,
            'customer_telephone' => optional($document->person)->telephone,
            'message_text' => "Su comprobante de pago electrónico {$this->number_full} ha sido generado correctamente, puede revisarlo en el siguiente enlace: ".url('')."/print/document/{$this->external_id}/a4"."",
            'sales_note' => $nvs,

            'send_to_pse' => $document->send_to_pse,
            'response_signature_pse' => optional($document->response_signature_pse)->message,
            'response_send_cdr_pse' => optional($document->response_send_cdr_pse)->message,
            
        ];
        return $data;
    }
}
