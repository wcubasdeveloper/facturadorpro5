<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Tenant\Configuration;


/**
 * Class CompanyResource
 *
 * @package App\Http\Resources\Tenant
 * @mixin JsonResource
 */
class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $configuration = Configuration::first();
        return [
            'id' => $this->id,
            'number' => $this->number,
            'name' => $this->name,
            'trade_name' => $this->trade_name,
            'soap_send_id' => $this->soap_send_id,
            'soap_type_id' => $this->soap_type_id,
            'soap_username' => $this->soap_username,
            'soap_password' => $this->soap_password,
            'soap_url' => $this->soap_url,
            'certificate' => $this->certificate,
            'certificate_due' => $this->certificate_due,
            'logo' => $this->logo,
            'detraction_account' => $this->detraction_account,
            'logo_store' => $this->logo_store,
            'operation_amazonia' => (bool) $this->operation_amazonia,
            'config_system_env' => (bool)$configuration->config_system_env,
            'img_firm' => $this->img_firm,
            'favicon' => $this->favicon,
            'cod_digemid' => $this->cod_digemid,
            'is_pharmacy' => $configuration->isPharmacy(),
            'integrated_query_client_id' => $this->integrated_query_client_id,
            'integrated_query_client_secret' => $this->integrated_query_client_secret,

            'send_document_to_pse' => $this->send_document_to_pse,
            'url_send_cdr_pse' => $this->url_send_cdr_pse,
            'url_signature_pse' => $this->url_signature_pse,
            'client_id_pse' => $this->client_id_pse,

        ];
    }
}
