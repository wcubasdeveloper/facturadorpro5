<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * @package App\Http\Resources\Tenant
 */
class UserResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request
     *
     * @return array
     */
    public function toArray($request) {
        $modules = $this->getCurrentModuleByTenant()
                        ->pluck('module_id')
                        ->toArray();
        $levels = $this->getCurrentModuleLevelByTenant()
                       ->pluck('module_level_id')
                       ->toArray();


        return [
            'id'               => $this->id,
            'email'            => $this->email,
            'name'             => $this->name,
            'api_token'        => $this->api_token,
            'establishment_id' => $this->establishment_id,
            'type'             => $this->type,
            'zone_id'             => $this->zone_id,
            'modules'          => $modules,
            'levels'           => $levels,
            'locked'           => (bool)$this->locked,
            'document_id'      => $this->document_id,
            'permission_edit_cpe' => $this->permission_edit_cpe,
            'recreate_documents' => $this->recreate_documents,
            'series_id'        => ($this->series_id == 0) ? null : $this->series_id,
            'create_payment' => $this->create_payment,
            'delete_payment' => $this->delete_payment,
        ];
    }
}
