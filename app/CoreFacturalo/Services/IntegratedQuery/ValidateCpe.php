<?php

namespace App\CoreFacturalo\Services\IntegratedQuery;
 
use Exception;
use Carbon\Carbon;

class ValidateCpe
{

    const BASE_URL = 'https://api.sunat.gob.pe/v1/contribuyente/contribuyentes';
    
    protected $company_number;
    protected $document_type_id;
    protected $series;
    protected $number;
    protected $date_of_issue;
    protected $total;
    protected $token;
    
    protected $document_state = [
        '0' => '-1', //'NO EXISTE' custom code
        '1' => '05', //'ACEPTADO'
        '2' => '11', //'ANULADO'
    ];

    public function __construct($token, $company_number, $document_type_id, $series, $number, $date_of_issue, $total)
    {
        $this->company_number = $company_number;
        $this->document_type_id = $document_type_id;
        $this->series = $series;
        $this->number = $number;
        $this->date_of_issue = Carbon::parse($date_of_issue)->format('d/m/Y');
        $this->total = $total;
        $this->token = $token;
    }


    public function search()
    {

        try {

            $form_params = [
                'numRuc' => $this->company_number,
                'codComp' => $this->document_type_id,
                'numeroSerie' => $this->series,
                'numero' => $this->number,
                'fechaEmision' => $this->date_of_issue,
                'monto' => $this->total,
            ];


            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => self::BASE_URL."/{$this->company_number}/validarcomprobante",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($form_params),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer {$this->token}",
                    'Content-Type: application/json'
                ),
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);

            $res = json_decode($response, true);

            // dd($res);

            if($res['success']){

                return [
                    'success' => $res['success'],
                    'message' => $res['message'],
                    'data' => [
                        'state_type_id' =>  isset($this->document_state[ $res['data']['estadoCp'] ]) ? $this->document_state[ $res['data']['estadoCp'] ] : null,
                        'estadoCp' =>  $res['data']['estadoCp'] ?? null,
                        'estadoRuc' =>  $res['data']['estadoRuc'] ?? null,
                        'condDomiRuc' =>  $res['data']['condDomiRuc'] ?? null,
                        'observaciones' =>  $res['data']['observaciones'] ?? null,
                    ],
                ];

            }

            return $res;

        } catch (Exception $e) {

            return [
                'success' => false,
                'message' => "Code: {$e->getCode()} - Message: {$e->getMessage()}"
            ];

        }

    } 

}
