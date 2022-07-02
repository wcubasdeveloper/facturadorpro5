<?php

namespace Modules\Report\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\SaleNote;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Item\Models\Category;
use Modules\Report\Exports\DocumentExport;
use Modules\Report\Http\Resources\DocumentCollection;
use Modules\Report\Http\Resources\SaleNoteCollection;
use Modules\Report\Traits\ReportTrait;


class ReportDocumentController extends Controller
{
    use ReportTrait;



    public function filter() {

        $document_types = DocumentType::whereIn('id',[
                '01',// factura
                '03',// boleta
                '07', // nota de credito
                '08',// nota de debito
                '80', // nota de venta
            ])->get();

        $persons = $this->getPersons('customers');
        $sellers = $this->getSellers();

        $establishments = Establishment::all()->transform(function($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });
        $users = $this->getUsers();

        return compact('document_types','establishments','persons', 'sellers', 'users');
    }


    public function index() {
        return view('report::documents.index');
    }

    public function records(Request $request)
    {
        $documentTypeId = "01";
        if ($request->has('document_type_id')) {
            $documentTypeId = str_replace('"', '', $request->document_type_id);
        }
        $documentType = DocumentType::find($documentTypeId);
        if (null === $documentType) {
            $documentType = new DocumentType();
        }

        $classType = $documentType->getCurrentRelatiomClass();

        $records = $this->getRecords($request->all(), $classType);

        if ($classType == SaleNote::class) {
            return new SaleNoteCollection($records->paginate(config('tenant.items_per_page')));
        }
        return new DocumentCollection($records->paginate(config('tenant.items_per_page')));


    }



    public function pdf(Request $request) {
        set_time_limit (1800); // Maximo 30 minutos
        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $documentTypeId = "01";
        if ($request->has('document_type_id')) {
            $documentTypeId = str_replace('"', '', $request->document_type_id);
        }
        $documentType = DocumentType::find($documentTypeId);
        if (null === $documentType) {
            $documentType = new DocumentType();
        }

        $classType = $documentType->getCurrentRelatiomClass();
        $records = $this->getRecords($request->all(), $classType);
        $records= $records->get();

        $filters = $request->all();

        $pdf = PDF::loadView('report::documents.report_pdf', compact("records", "company", "establishment", "filters"))
            ->setPaper('a4', 'landscape');

        $filename = 'Reporte_Ventas_'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }

    public function pdfSimple(Request $request) {
        set_time_limit (1800); // Maximo 30 minutos
        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $documentTypeId = "01";
        if ($request->has('document_type_id')) {
            $documentTypeId = str_replace('"', '', $request->document_type_id);
        }
        $documentType = DocumentType::find($documentTypeId);
        if (null === $documentType) {
            $documentType = new DocumentType();
        }

        $classType = $documentType->getCurrentRelatiomClass();
        $records = $this->getRecords($request->all(), $classType);
        $records= $records->get();

        $filters = $request->all();

        $pdf = PDF::loadView('report::documents.report_pdf_simple', compact("records", "company", "establishment", "filters"))
            ->setPaper('a4', 'landscape');

        $filename = 'Reporte_Ventas_Simple'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }


    public function excel(Request $request) {
        
        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $documentTypeId = "01";
        if ($request->has('document_type_id')) {
            $documentTypeId = str_replace('"', '', $request->document_type_id);
        }
        $documentType = DocumentType::find($documentTypeId);
        if (null === $documentType) {
            $documentType = new DocumentType();
        }

        $classType = $documentType->getCurrentRelatiomClass();
        $records = $this->getRecords($request->all(), $classType);
        $records= $records->get();
        $filters = $request->all();

        //get categories
        $categories = [];
        $categories_services = [];

        if($request->include_categories == "true"){
            $categories = $this->getCategories($records, false);
            $categories_services = $this->getCategories($records, true);
        }

        $documentExport = new DocumentExport();
        $documentExport
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->filters($filters)
            ->categories($categories)
            ->categories_services($categories_services);
         // return $documentExport->view();
        return $documentExport->download('Reporte_Ventas_'.Carbon::now().'.xlsx');

    }


    public function getCategories($records, $is_service) {

        $aux_categories = collect([]);

        foreach ($records as $document) {

            $id_categories = $document->items->filter(function($row) use($is_service){
                return (($is_service) ? (!is_null($row->relation_item->category_id) && $row->item->unit_type_id === 'ZZ') : !is_null($row->relation_item->category_id)) ;
            })->pluck('relation_item.category_id');

            foreach ($id_categories as $value) {
                $aux_categories->push($value);
            }
        }

        return Category::whereIn('id', $aux_categories->unique()->toArray())->get();

    }


}
