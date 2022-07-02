<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\User;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Report\Http\Resources\ReportTipCollection;
use Modules\Pos\Models\Tip;
use App\CoreFacturalo\Helpers\Functions\FunctionsHelper;
use Modules\Report\Exports\ReportTipExport;


class ReportTipController extends Controller
{

    public function index() 
    {
        return view('report::tips.index');
    }
   
    public function records(Request $request)
    {
        $records = $this->getRecords($request->all());
        // dd($records->get());
        return new ReportTipCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function getRecords($request)
    {

        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        
        FunctionsHelper::setDateInPeriod($request, $date_start, $date_end);

        return Tip::with(['origin'])->whereBetween('origin_date_of_issue', [$date_start, $date_end])->latest();

    }

    public function getDataForReport(Request $request) 
    {
        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment; 
        $records = $this->getRecords($request->all())->get();

        return compact('records', 'company', 'establishment');
    }


    public function pdf(Request $request) 
    {
        $pdf = PDF::loadView('report::tips.report_pdf', $this->getDataForReport($request));

        return $pdf->download('Reporte_Propinas_'.date('YmdHis').'.pdf');
    }


    public function excel(Request $request) 
    {
        return (new ReportTipExport)
                ->data($this->getDataForReport($request))
                ->download('Reporte_Propinas_'.date('YmdHis').'.xlsx');

    }


}
