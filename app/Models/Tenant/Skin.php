<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Skin extends ModelTenant
{
    use UsesTenantConnection;

    protected $fillable = [
        'name',
        'filename',
        'status'
    ];

    /**
     * @return array
     */
    public function getCollectionData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'filename' => $this->filename,
            'status' => $this->status,
        ];
    }
}
