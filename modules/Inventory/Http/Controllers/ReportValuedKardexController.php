<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Inventory\Exports\ValuedKardexExport;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use App\Models\Tenant\Item;
use Carbon\Carbon;
use Modules\Inventory\Http\Resources\ReportValuedKardexCollection;
use Modules\Report\Traits\ReportTrait;
use Modules\Inventory\Helpers\InventoryValuedKardex;
use Modules\Inventory\Exports\ValuedKardexFormatSunatExport;


class ReportValuedKardexController extends Controller
{

    use ReportTrait;

    public function filter()
    {

        $establishments = Establishment::all()->transform(function ($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });

        return compact('establishments');
    }


    public function index()
    {

        return view('inventory::reports.valued_kardex.index');
    }


    public function records(Request $request)
    {
        $records = $this->getRecords($request->all());

        return new ReportValuedKardexCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function getRecords($request)
    {

        $data_of_period = $this->getDataOfPeriod($request);

        $params = (object)[
             'establishment_id' => $request['establishment_id']??0,
            'date_start' => $data_of_period['d_start'],
            'date_end' => $data_of_period['d_end'],
        ];
        if(isset($request['stablishmentKardexAll']) && $request['stablishmentKardexAll'] = 1){
            $params = (object)[
                'date_start' => $data_of_period['d_start'],
                'date_end' => $data_of_period['d_end'],
            ];
        }
        $records = $this->data($params);

        return $records;

    }


    /**
     * @param object $params
     * @return Item|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    private function data($params)
    {
        return Item::whereFilterValuedKardex($params)
            ->whereNotService()
            ->orderBy('description');

    }


    public function excel(Request $request)
    {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $records = InventoryValuedKardex::getTransformRecords($this->getRecords($request->all())->get());
        $valuedKardexExport = new ValuedKardexExport();
        $valuedKardexExport
            ->records($records)
            ->company($company)
            ->establishment($establishment);

        return $valuedKardexExport->download('Reporte_Kardex_Valorizado_' . Carbon::now() . '.xlsx');

    }


    public function excelFormatSunat(Request $request)
    {

        // dd($request->all());
        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : null;
        $data_of_period = $this->getDataOfPeriod($request);


        $params = (object)[
            'item_id' => $request['item_id'],
            'establishment_id' => $request['establishment_id'],
            'date_start' => $data_of_period['d_start'],
            'date_end' => $data_of_period['d_end'],
        ];

        $data = InventoryValuedKardex::getDataFormatSunat($params);
        $additionalData = InventoryValuedKardex::getDataAdditional($request, $params, $data['item']);
        $records = $data['records'];


        $valuedKardexFormatSunatExport = new ValuedKardexFormatSunatExport();
        $valuedKardexFormatSunatExport
            ->additionalData($additionalData)
            ->records($records)
            ->company($company)
            ->establishment($establishment);

        return $valuedKardexFormatSunatExport->download('Reporte_Kardex_Valorizado_Sunat_13_1' . Carbon::now() . '.xlsx');

    }

}
