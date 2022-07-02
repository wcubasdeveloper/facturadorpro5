<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Report\Models\ReportConfiguration;
use Modules\Report\Http\Resources\ReportConfigurationCollection;
use Modules\Report\Http\Requests\ReportConfigurationRequest;


class ReportConfigurationController extends Controller
{

    public function records()
    {
        return new ReportConfigurationCollection(ReportConfiguration::get());
    }


    public function store(ReportConfigurationRequest $request)
    {
        $id = $request->input('id');
        $record = ReportConfiguration::findOrFail($id);
        $record->fill($request->all());
        $record->save();

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];
    }

    
}