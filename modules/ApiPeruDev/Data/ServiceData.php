<?php

    namespace Modules\ApiPeruDev\Data;

    use App\Models\Tenant\Company;
    use App\Models\Tenant\ExchangeRate;
    use GuzzleHttp\Client;
    use App\Models\System\Configuration as SystemConfiguration;
    use App\Models\Tenant\Configuration as TenantConfig;
    use App\Models\System\TrackApiPeruServices as SystemTrackApiPeruService;
    use App\Models\Tenant\TrackApiPeruServices as TenantTrackApiPeruService;
    use Illuminate\Support\Facades\URL;

    class ServiceData
    {
        protected $client;
        /**
         * @var TenantTrackApiPeruService|SystemConfiguration
         */
        protected $trackApi;
        /**
         * @var SystemTrackApiPeruService|TenantTrackApiPeruService
         */
        protected $configuration;
        /** @var Company */
        protected $company;
        protected $parameters;

        public function __construct()
        {
            $prefix = env('PREFIX_URL',null);
            $prefix = !empty($prefix)?$prefix.".":'';
            $app_url = $prefix. env('APP_URL_BASE');
            $url =  $_SERVER['HTTP_HOST']??null;
            $company = null;
            // Desde admin
            $configuration = SystemConfiguration::query()->first();
            $trackApi = new SystemTrackApiPeruService();

            if($url !== $app_url) {
                // desde cliente
                $configuration = TenantConfig::query()->first();
                $trackApi = new TenantTrackApiPeruService();
                $company = Company::first();
                if ($configuration->UseCustomApiPeruToken() == false) {
                    $configuration = SystemConfiguration::query()->first();
                    $trackApi = new SystemTrackApiPeruService();
                }
            }

            $url = $configuration->url_apiruc = !'' ? $configuration->url_apiruc : config('configuration.api_service_url');
            $token = $configuration->token_apiruc = !'' ? $configuration->token_apiruc : config('configuration.api_service_token');
            $this->configuration = $configuration;
            $this->trackApi = $trackApi;
            $this->company = $company;


            $this->client = new Client(['base_uri' => $url]);
            $this->parameters = [
                'http_errors' => false,
                'connect_timeout' => 10,
                'verify' => false,
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Accept' => 'application/json',
                ],
            ];
        }
        /**
         * 1 => sunat/dni
         * 2 => validacion_multiple_cpe
         * 3 => CPE
         * 4 => tipo_de_cambio
         * 5 => printer_ticket
         *
         * @param int $service
         */
        public function saveService($service = 0, $response = [])
        {

            if (isset($response['message']) &&
                strpos($response['message'], 'Ha superado la cantidad de consultas mensuales') !== false) {
                // Si se ha superado la cantidad, no hace nada.
                return $this;

            }
            $number = null;
            if(!empty($this->company)){
                $number = $this->company->number;
            }
            $this->trackApi->setService($number, $service);
            $this->trackApi->push();
            return $this;

        }

        public function service($type, $number)
        {

            $res = $this->client->request('GET', '/api/' . $type . '/' . $number, $this->parameters);
            $response = json_decode($res->getBody()->getContents(), true);

            $res_data = [];
            if ($response['success']) {
                $data = $response['data'];
                if ($type === 'dni') {
                    $department_id = '';
                    $province_id = null;
                    $district_id = null;
                    $address = null;
                    if (key_exists('source', $response) && $response['source'] === 'apiperu.dev') {
                        if (strlen($data['ubigeo_sunat'])) {
                            $department_id = $data['ubigeo'][0];
                            $province_id = $data['ubigeo'][1];
                            $district_id = $data['ubigeo'][2];
                            $address = $data['direccion'];
                        }
                    } else {
                        $department_id = $data['ubigeo'][0];
                        $province_id = $data['ubigeo'][1];
                        $district_id = $data['ubigeo'][2];
                        $address = $data['direccion'];
                    }

                    $res_data = [
                        'name' => $data['nombre_completo'],
                        'trade_name' => '',
                        'location_id' => $district_id,
                        'address' => $address,
                        'department_id' => $department_id,
                        'province_id' => $province_id,
                        'district_id' => $district_id,
                        'condition' => '',
                        'state' => '',
                    ];
                }

                if ($type === 'ruc') {
                    $address = '';
                    $department_id = null;
                    $province_id = null;
                    $district_id = null;
                    if (key_exists('source', $response) && $response['source'] === 'apiperu.dev') {
                        if (strlen($data['ubigeo_sunat'])) {
                            $department_id = $data['ubigeo'][0];
                            $province_id = $data['ubigeo'][1];
                            $district_id = $data['ubigeo'][2];
                            $address = $data['direccion'];
                        }
                    } else {
                        $department_id = $data['ubigeo'][0];
                        $province_id = $data['ubigeo'][1];
                        $district_id = $data['ubigeo'][2];
                        $address = $data['direccion'];
                    }

                    $res_data = [
                        'name' => $data['nombre_o_razon_social'],
                        'trade_name' => '',
                        'address' => $address,
                        'department_id' => $department_id,
                        'province_id' => $province_id,
                        'district_id' => $district_id,
                        'condition' => $data['condicion'],
                        'state' => $data['estado'],
                    ];
                }
                $response['data'] = $res_data;
            }
            $this->saveService(1,$response);
            return $response;
        }

        public function massive_validate_cpe($data)
        {
            $this->parameters['form_params'] = $data;
            $res = $this->client->request('POST', '/api/validacion_multiple_cpe', $this->parameters);
            $this->trackApi->push();
            $this->saveService(2);

            return json_decode($res->getBody()->getContents(), true);
        }

        public function cpe($company_number, $document_type_id, $series, $number, $date_of_issue, $total)
        {
            $form_params = [
                'ruc_emisor' => $company_number,
                'codigo_tipo_documento' => $document_type_id,
                'serie_documento' => $series,
                'numero_documento' => $number,
                'fecha_de_emision' => $date_of_issue,
                'total' => $total
            ];

            $this->parameters['form_params'] = $form_params;
            $res = $this->client->request('POST', '/api/cpe', $this->parameters);
            $this->saveService(3);

            return json_decode($res->getBody()->getContents(), true);
        }

        public function exchange($date)
        {
            $exchange = ExchangeRate::query()->where('date', $date)->first();
            if ($exchange) {
                return [
                    'date' => $date,
                    'purchase' => $exchange->purchase,
                    'sale' => $exchange->sale
                ];
            }
            $form_params = [
                'fecha' => $date,
            ];

            $this->parameters['form_params'] = $form_params;
            $res = $this->client->request('POST', '/api/tipo_de_cambio', $this->parameters);
            $response = json_decode($res->getBody()->getContents(), true);

            if ($response['success']) {
                $data = $response['data'];
                ExchangeRate::query()->create([
                    'date' => $data['fecha_busqueda'],
                    'date_original' => $data['fecha_sunat'],
                    'sale_original' => $data['venta'],
                    'sale' => $data['venta'],
                    'purchase_original' => $data['compra'],
                    'purchase' => $data['compra'],
                ]);

                return [
                    'date' => $data['fecha_busqueda'],
                    'purchase' => $data['compra'],
                    'sale' => $data['venta']
                ];
            }
            $this->saveService(4);

            return [
                'date' => $date,
                'purchase' => 1,
                'sale' => 1,
            ];
        }

        public function printer_ticket($data)
        {
            $this->parameters['form_params'] = $data;
            $res = $this->client->request('POST', '/api/printer_ticket', $this->parameters);
            $this->saveService(5);

            return json_decode($res->getBody()->getContents(), true);
        }
    }
