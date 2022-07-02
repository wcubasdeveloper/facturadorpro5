<?php

namespace Modules\Report\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Company;
use App\Models\Tenant\Item;
use Carbon\Carbon;
use Modules\Report\Http\Resources\SaleConsolidatedCollection;
use Modules\Report\Traits\ReportTrait;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\DocumentItem;
use Illuminate\Support\Facades\DB;
use Modules\Report\Exports\SaleConsolidatedExport;
use Modules\Report\Exports\SaleConsolidatedTotalExport;


class ReportSaleConsolidatedController extends Controller
{
    use ReportTrait;

    public function filter()
    {


        $persons = $this->getPersons('customers');
        $date_range_types = $this->getDateRangeTypes(true);
        $order_state_types = [];
        $sellers = $this->getSellers();
        $document_types = $this->getCIDocumentTypes();
        $establishment_id = $this->getEstablishment();
        $series = $this->getSeries($document_types);
        $users = $this->getUsers();

        return compact(
             'users',
             'persons',
             'date_range_types',
             'order_state_types',
             'sellers',
             'document_types',
             'series',
             'establishment_id'
        );
    }


    public function index() {

        return view('report::sales_consolidated.index');
    }

    public function records(Request $request)
    {
        $records = $this->getRecordsSalesConsolidated($request->all());

        return new SaleConsolidatedCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function totalsByItem(Request $request)
    {

        // $records = $this->getRecordsSalesConsolidated($request->all())->groupBy('item_id')->get();
        $records = $this->groupTotalsByItem($this->getRecordsSalesConsolidated($request->all())->get());


        return $records->map(function(\Illuminate\Database\Eloquent\Collection $row, $key){
            $unit_type_id = 'ZZ';
            $first =$row->first();
            $quantity = $row->sum('quantity');
            $brand = "";
            $category = "";
            $unit_price = 1;


            if (property_exists($first->item, 'presentation') && $first->item->presentation) {
                $unit_type_id = $first->item->presentation->unit_type_id;
            }
            if($unit_type_id !== 'ZZ'){
                $item = \App\Models\Tenant\Item::select('brand_id')->where('internal_id',$first->item->internal_id)->first();
                if(!empty($item)){
                    $brand = $item->brand;
                    $brand = $brand['name'];
                    $category = $item->category;
                    $category = $category['name'];
                }
            }
            if (property_exists($first->item,'unit_price')) {
                $unit_price = $first->item->unit_price * 1;
                if(!is_numeric($unit_price)){
                    $unit_price = 1;
                }
            }
            $total_sale = $unit_price * ($row->sum('quantity') * 1);

            // obtener item id y unit_type_id
            $item_unit_type = explode('-', $key);
            $row_item_id = $item_unit_type[0] ?? $first->item_id;
            $row_unit_type_id = $item_unit_type[1] ?? $first->relation_item->unit_type_id;
            
            return [
                'item_id' => $row_item_id,
                // 'item_id' => $key,
                'brand' => $brand,
                'total_sale' => $total_sale,
                'category' => $category,
                'item_internal_id' => $first->relation_item->internal_id,
                'item_unit_type_id' => $row_unit_type_id,
                // 'item_unit_type_id' => $first->relation_item->unit_type_id,
                'item_description' => $first->item->description,
                'quantity' => number_format($quantity, 4, ".", ""),
            ];
        });

    }

        
    /**
     * 
     * Agrupar items por item_id y unit_type_id, para ventas individuales y por presentaciones
     *
     * @param  array $records
     * @return array
     */
    public function groupTotalsByItem($records)
    {
        return $records->groupBy(function($row){

                    $item_unit_type_id = $row->item->unit_type_id ?? false;
                    $group_key = $row->item_id;

                    if($item_unit_type_id)
                    {
                        $group_key .= "-".$item_unit_type_id; 
                    }

                    return $group_key; 
                });
    }


    public function getRecordsSalesConsolidated($request){

        // dd($request);
        $records = $this->dataSalesConsolidated($request);

        return $records;

    }


    private function dataSalesConsolidated($request)
    {

        $document_types = isset($request['document_type_id']) ? json_decode($request['document_type_id']) : [];
        $document_items = DocumentItem::whereDefaultDocumentType($request);
        if (!empty($document_types)) {
            $nota_venta = null;
            $document_items = $document_items->wherein('document_type_id', $document_types);
            if (in_array('80', $document_types)) {
                $request['document_type_id'] = '80';
                $nota_venta = SaleNoteItem::whereDefaultDocumentType($request);
            }
            $data = ($nota_venta != null) ? $document_items->union($nota_venta) : $document_items;
        } else {
            $sale_note_items = SaleNoteItem::whereDefaultDocumentType($request);
            $data = $document_items->union($sale_note_items);
        }


        return $data;

        $document_type_id = $request['document_type_id'];

        switch ($document_type_id) {

            case '01':
            case '03':
                $data = DocumentItem::whereDefaultDocumentType($request)->whereDocumentTypeId($document_type_id);
                break;

            case '80':
                $data = SaleNoteItem::whereDefaultDocumentType($request);
                break;

            default:
                $document_items = DocumentItem::whereDefaultDocumentType($request);
                $sale_note_items = SaleNoteItem::whereDefaultDocumentType($request);
                $data = $document_items->union($sale_note_items);

                break;
        }

        return $data;

    }


    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecordsSalesConsolidated($request->all())->get();
        $params = $request->all();

         // return View('report::sales_consolidated.report_pdf', compact("records", "company", "establishment", "params"));;
        /** @var \Barryvdh\DomPDF\PDF $pdf */
        $pdf = PDF::loadView('report::sales_consolidated.report_pdf', compact("records", "company", "establishment", "params"));

        $filename = 'Reporte_Consolidado_Items_Ventas_'.date('YmdHis');

        return $pdf->stream($filename.'.pdf');
    }


    public function excel(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $records = $this->getRecordsSalesConsolidated($request->all())->get();
        $params = $request->all();
        $filename = 'Reporte_Consolidado_Items_Ventas_'.date('YmdHis');

        $saleConsolidatedExport = new SaleConsolidatedExport();
        $saleConsolidatedExport
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->params($params);
        // return  $saleConsolidatedExport->view();
        return $saleConsolidatedExport->download($filename.'.xlsx');

    }


    public function pdfTotals(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->totalsByItem($request)->sortBy('item_id');
        $params = $request->all();
        /** @var \Barryvdh\DomPDF\PDF $pdf */

        $pdf = PDF::loadView('report::sales_consolidated.report_pdf_totals', compact("records", "company", "establishment", "params"));

        $filename = 'Reporte_Consolidado_Items_Ventas_Totales_'.date('YmdHis');

        return $pdf->stream($filename.'.pdf');
    }


    public function pdfTicketsTotal(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->totalsByItem($request)->sortBy('item_id');
        $params = $request->all();
        /** @var \Barryvdh\DomPDF\PDF $pdf */
        $height =( 5.8 / 2.54) * 72; // Cm a inches
        $customPaper = [0,0,$height,1440];
        $pdf = PDF::loadView('report::sales_consolidated.report_pdf_totals_ticket', compact("records", "company", "establishment", "params"))
            ->setPaper($customPaper);

        $filename = 'Reporte_Consolidado_Items_Ventas_Totales_'.date('YmdHis');

        return $pdf->stream($filename.'.pdf');
    }
    public function pdfTicketsTotal80(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->totalsByItem($request)->sortBy('item_id');
        $params = $request->all();
        /** @var \Barryvdh\DomPDF\PDF $pdf */
        $height =( 8 / 2.54) * 72; // Cm a inches
        $customPaper = [0,0,$height,1440];
        $pdf = PDF::loadView('report::sales_consolidated.report_pdf_totals_ticket_80', compact("records", "company", "establishment", "params"))
            ->setPaper($customPaper);


        $filename = 'Reporte_Consolidado_Items_Ventas_Totales_'.date('YmdHis');

        return $pdf->stream($filename.'.pdf');
    }


    public function excelTotals(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->totalsByItem($request)->sortBy('item_id');
        $params = $request->all();
        $filename = 'Reporte_Consolidado_Items_Ventas_Totales_'.date('YmdHis');

        return (new SaleConsolidatedTotalExport)
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->params($params)
                ->download($filename.'.xlsx');

    }

}
