<?php

    namespace App\Models\System;

    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Database\Eloquent\Model;

    /**
     * Class TrackApiPeruService
     *
     * @property int         $id
     * @property int|null    $service
     * @property int|null    $client_id
     * @property string|null $ruc
     * @property Carbon|null $date_of_issue
     * @package App\Models\System
     */
    class TrackApiPeruServices extends Model
    {

        public $timestamps = false;
        protected $perPage = 25;
        protected $casts = [
            'service' => 'int',
            'client_id' => 'int',
        ];


        protected $fillable = [
            'service',
            'date_of_issue',
            'ruc',
            'client_id',
        ];



        /**
         * Establece una consulta de un servicio, Requiere el ruc
         * 1 => sunat/dni
         * 2 => validacion_multiple_cpe
         * 3 => CPE
         * 4 => tipo_de_cambio
         * 5 => printer_ticket
         *
         * @param string $ruc
         * @param int? $service
         *
         * @return $this
         */
        public function setService($ruc,$service = 0)
        {
            $this->date_of_issue = Carbon::now();
            $this->service = $service;
            $this->ruc = $ruc;
            $client = Client::where('number',$ruc)->first();
            if(empty($client)) $client = new Client();
            $this->client_id = (int)$client->id;

            return $this;
        }
    }
