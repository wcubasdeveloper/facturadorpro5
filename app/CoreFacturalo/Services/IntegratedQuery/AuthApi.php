<?php

namespace App\CoreFacturalo\Services\IntegratedQuery;
 
use App\Models\Tenant\Company;
use Exception;

class AuthApi
{

    const GRANT_TYPE = 'client_credentials';
    const SCOPE = 'https://api.sunat.gob.pe/v1/contribuyente/contribuyentes';

    public function getToken()
    {

        try {

            $company = Company::active();

            if(!$company->integrated_query_client_id || !$company->integrated_query_client_secret){
                return [
                    'success' => false,
                    'message' => 'No ha configurado correctamente el campo client_id o client_secret',
                ];
            }

            $curl = curl_init();
            
            $form_params = [
                'grant_type' => self::GRANT_TYPE,
                'scope' => self::SCOPE,
                'client_id' => $company->integrated_query_client_id,
                'client_secret' => $company->integrated_query_client_secret, 
            ];

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api-seguridad.sunat.gob.pe/v1/clientesextranet/{$company->integrated_query_client_id}/oauth2/token",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => http_build_query($form_params),
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded',
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);

            $data = json_decode($response, true);

            if(array_key_exists('access_token', $data)){
                
                return [
                    'success' => true,
                    'data' => [
                        'access_token' => $data['access_token'],
                        'token_type' => $data['token_type'],
                        'expires_in' => $data['expires_in'],
                    ],
                ];
            }

            // dd($data);

            $error_description = $data['error_description'] ?? '';
            $error = $data['error'] ?? '';
            
            return [
                'success' => false,
                'message' => 'Error al obtener token - error_description: '.$error_description.' error: '.$error
            ];

            
        } catch (Exception $e) {

            return [
                'success' => false,
                'message' => "Code: {$e->getCode()} - Message: {$e->getMessage()}"
            ];

        }

    } 

}
