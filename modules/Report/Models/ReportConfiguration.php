<?php

namespace Modules\Report\Models;

use App\Models\Tenant\ModelTenant;

class ReportConfiguration extends ModelTenant
{

    protected $fillable = [
        'route_name',
        'name',
        'convert_pen',
        'route_path',
    ];


    protected $casts = [
        'convert_pen' => 'bool',
    ];

    
    /**
     * 
     * Filtro por ruta para validar si aplica conversión a soles
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param  string $route_name
     * @return \Illuminate\Database\Eloquent\Builder
     * 
     */
    public function scopeWhereApplyConversion($query, $route_name)
    {
        return $query->select('convert_pen')->where('route_name', $route_name);
    }


    public function getRowResource()
    {
        return [
            'id' => $this->id,
            'route_name' => $this->route_name,
            'name' => $this->name,
            'convert_pen' => $this->convert_pen,
            'route_path' => $this->route_path,
        ];
    }


}
