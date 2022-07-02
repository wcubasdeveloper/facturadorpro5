<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Modules\Report\Exports\CommissionDetailExport;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\User;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\Company;
use Carbon\Carbon;
use Modules\Report\Http\Resources\ReportCommissionDetailCollection;

class ReportCommissionDetailController extends Controller
{


    public function filter() {

        $document_types = [];

        $establishments = Establishment::all()->transform(function($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });

        return compact('document_types','establishments');
    }


    public function index() {

        return view('report::commissions_detail.index');
    }

    public function records(Request $request)
    {
        $sales_notes = $this->getRecords($request->all(), SaleNoteItem::class);
        $documents = $this->getRecords($request->all(), DocumentItem::class);
        $merge = ($sales_notes->get())->merge($documents->get());

        return new ReportCommissionDetailCollection($merge);
    }


    public function getRecords($request, $model){

        
        $establishment_id = $request['establishment_id'];
        $period = $request['period'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        $month_start = $request['month_start'];
        $month_end = $request['month_end'];
        $item_id = $request['item_id'];


        $d_start = null;
        $d_end = null;
        /** @todo: Eliminar periodo, fechas y cambiar por

        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        \App\CoreFacturalo\Helpers\Functions\FunctionsHelper\FunctionsHelper::setDateInPeriod($request, $date_start, $date_end);
         */

        switch ($period) {
            case 'month':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_start.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'between_months':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'date':
                $d_start = $date_start;
                $d_end = $date_start;
                break;
            case 'between_dates':
                $d_start = $date_start;
                $d_end = $date_end;
                break;
        }

        $records = $this->data($establishment_id, $d_start, $d_end, $model, $item_id);

        return $records;

    }


    private function data($establishment_id, $date_start, $date_end, $model, $item_id)
    {

        if($model == 'App\Models\Tenant\DocumentItem') {

            if($establishment_id){

                    $data = $model::whereHas('document',function($query) use($date_start, $date_end, $establishment_id){
                        $query->whereBetween('date_of_issue', [$date_start, $date_end])
                        ->whereIn('document_type_id', ['01','03'])
                        ->where('establishment_id', $establishment_id)
                        ->whereStateTypeAccepted();
                    });
                    
    
            }else{
    
             
                
                $data = $model::whereHas('document',function($query) use($date_start, $date_end){
                            $query->whereBetween('date_of_issue', [$date_start, $date_end])
                            ->whereIn('document_type_id', ['01','03'])
                            ->whereStateTypeAccepted();
                        });
            }

            if ($item_id) {
                $data = $data->where('item_id', $item_id);
            }
    
            return $data;
            

        }
        else if ($model == 'App\Models\Tenant\SaleNoteItem'){

            if($establishment_id){

                $data = $model::whereHas('sale_note',function($query) use($date_start, $date_end, $establishment_id){
                    $query->whereBetween('date_of_issue', [$date_start, $date_end])
                        ->where('establishment_id', $establishment_id)
                        ->whereStateTypeAccepted();
                    });
    
            }else{
    
                    $data = $model::whereHas('sale_note',function($query) use($date_start, $date_end){
                                $query->whereBetween('date_of_issue', [$date_start, $date_end])
                                    ->whereStateTypeAccepted();
                                });
                        
            }
    
            if ($item_id) {
                $data = $data->where('item_id', $item_id);
            }
    
            return $data;

        }

    }


    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $sales_notes = $this->getRecords($request->all(), SaleNoteItem::class);
        $documents = $this->getRecords($request->all(), DocumentItem::class);
        $records = ($sales_notes->get())->merge($documents->get());


        $pdf = PDF::loadView('report::commissions_detail.report_pdf', compact("records", "company", "establishment"))->setPaper('a4', 'landscape');

        $filename = 'Reporte_Utilidades_Detallado'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }




    public function excel(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $sales_notes = $this->getRecords($request->all(), SaleNoteItem::class);
        $documents = $this->getRecords($request->all(), DocumentItem::class);
        $records = ($sales_notes->get())->merge($documents->get());
        
        return (new CommissionDetailExport)
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->download('Reporte_Comision_utilidades_Vendedor'.Carbon::now().'.xlsx');

    }
}
