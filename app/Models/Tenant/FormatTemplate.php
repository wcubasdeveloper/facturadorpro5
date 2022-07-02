<?php

namespace App\Models\Tenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class FormatTemplate extends ModelTenant
{
    use UsesTenantConnection;

    protected $fillable = [
    	'id',
    	'formats',
        'urls',
        'is_custom_ticket'
    ];

    public function getUrlAttribute($value)
    {
        return (is_null($value))?null:(object) json_decode($value);
    }

    public function setUrlAttribute($value)
    {
        $this->attributes['urls'] = (is_null($value))?null:json_encode($value);
    }

    public function getCollectionData()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->formats,
            'urls' => json_decode($this->urls),
        ];
        return $data;
    }
}
