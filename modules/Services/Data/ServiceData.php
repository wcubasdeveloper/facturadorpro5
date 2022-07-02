<?php

namespace Modules\Services\Data;

use GuzzleHttp\Client;
use App\Models\System\Configuration;

class ServiceData
{
    public static function service($type, $number)
    {
        $configuration = Configuration::first();

        $url = $configuration->url_apiruc =! '' ? $configuration->url_apiruc : config('configuration.api_service_url');
        $token = $configuration->token_apiruc =! '' ? $configuration->token_apiruc : config('configuration.api_service_token');

        $client = new Client(['base_uri' => $url, 'verify' => false]);
        $parameters = [
            'http_errors' => false,
            'connect_timeout' => 5,
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept' => 'application/json',
            ],
        ];

        $res = $client->request('GET', '/api/'.$type.'/'.$number, $parameters);
        $response = json_decode($res->getBody()->getContents(), true);

        return $response;
    }

    /*
     * apiperu.net.pe --- para verificar envio de datos y url
     */
    public function validar_cpe($ruc,$usuario,$clave,$file)
    {
        try {
            $configuration = Configuration::first();
            //  dd($configuration->url_apiruc,$configuration->token_apiruc,$ruc,$usuario,$clave,$file);
            $this->client = new Client(['base_uri' => $configuration->url_apiruc, 'verify' => false, 'http_errors' => false]);
            $curl = [
                CURLOPT_URL => $configuration->url_apiruc.'/api/validar/txt',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array('file'=> new \CURLFILE(public_path('storage/txt/'.$file)),'ruc' => $ruc,'usuario_sol' => $usuario,'clave_sol' => $clave),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.$configuration->token_apiruc,
                ),
            ];
            $responses = $this->client->request(strtoupper("POST"),'/api/validar/txt', [
                'curl' => $curl,
            ]);
            return $responses->getBody()->getContents();

        } catch (GuzzleHttp\Exception\RequestException $exception) {
            return $exception->getResponse()->getBody();
        }

    }
}
