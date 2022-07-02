<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Company;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\DownloadTray;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\Inventory\Exports\InventoryExport;
use Modules\Inventory\Models\ItemWarehouse;
use Modules\Inventory\Models\Warehouse;
use Modules\Item\Models\Brand;
use Modules\Item\Models\Category;
use Hyn\Tenancy\Models\Hostname;
use App\Models\System\Client;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Jobs\ProcessInventoryReport;
use Modules\Inventory\Http\Resources\ReportInventoryCollection;



class ReportInventoryController extends Controller
{
    public function tables()
    {
        return [
            'warehouses' => Warehouse::query()->select('id', 'description')->get(),
            'categories' => Category::query()->select('id', 'name')->get(),
            'brands' => Brand::query()->select('id', 'name')->get(),
        ];
    }

    public function index()
    {
//        $warehouse_id = $request->input('warehouse_id');
//        $reports = $this->getRecords($warehouse_id)->paginate(config('tenant.items_per_page'));
//
//        $warehouses = Warehouse::query()->select('id', 'description')->get();
//
//        return view('inventory::reports.inventory.index', compact('reports', 'warehouses'));
        return view('inventory::reports.inventory.index');
    }

    public function records(Request $request)
    {
        $warehouse_id = $request->input('warehouse_id');
        //$brand_id = (int)$request->brand_id;
        //$category_id = (int)$request->category_id;
        //$active = $request->active;
        $filter = $request->input('filter');
        //$date_end = $request->has('date_end') ? $request->date_end : null;
        //$date_start = $request->has('date_start') ? $request->date_start : null;
        $records = $this->getRecords($warehouse_id, $filter, $request);

        return new ReportInventoryCollection($records->paginate(50), $filter);

    }

    /**
     * @param int $warehouse_id Id de almacen
     *
     * @return Builder
     */
    private function getRecords($warehouse_id = 0, $filter, $request) 
    {
        $query = ItemWarehouse::with(['warehouse', 'item'=> function ($query){
                                $query->select('id', 'barcode', 'internal_id', 'description', 'category_id', 'brand_id','stock_min', 'sale_unit_price', 'purchase_unit_price', 'model', 'date_of_due' );
                                $query->with(['category', 'brand']);
                                $query->without(['item_type', 'unit_type', 'currency_type', 'warehouses', 'item_unit_types', 'tags']);
                               }])
                              ->whereHas('item', function ($q) {
                                  $q->where([
                                                ['item_type_id', '01'],
                                                ['unit_type_id', '!=', 'ZZ'],
                                            ])
                                    ->whereNotIsSet();
                              });

        if ($filter === '02') {
            //$add = ($stock < 0);
            $query->where('stock', '<=', 0);

        }

        if ($filter === '03') {
            //$add = ($stock == 0);
            $query->where('stock', 0);
        }

        if ($filter === '04') {
            //$add = ($stock > 0 && $stock <= $item->stock_min);
            //$query->where('stock', 0);

            $query = ItemWarehouse::with(['warehouse', 'item'=> function ($query){
                $query->select('id', 'barcode', 'internal_id', 'description', 'category_id', 'brand_id','stock_min', 'sale_unit_price', 'purchase_unit_price', 'model', 'date_of_due' );
                $query->with(['category', 'brand']);
                $query->without(['item_type', 'unit_type', 'currency_type', 'warehouses', 'item_unit_types', 'tags']);
               }])
              ->whereHas('item', function ($q) {
                  $q->where([
                                ['item_type_id', '01'],
                                ['unit_type_id', '!=', 'ZZ'],
                            ])
                    ->whereNotIsSet()
                    ->whereStockMin();
              })->where('stock', '>', 0);

        }


        if ($filter === '05') {
            //$add = ($stock > $item->stock_min);

            $query = ItemWarehouse::with(['warehouse', 'item'=> function ($query){
                $query->select('id', 'barcode', 'internal_id', 'description', 'category_id', 'brand_id','stock_min', 'sale_unit_price', 'purchase_unit_price', 'model', 'date_of_due' );
                $query->with(['category', 'brand']);
                $query->without(['item_type', 'unit_type', 'currency_type', 'warehouses', 'item_unit_types', 'tags']);
               }])
              ->whereHas('item', function ($q) {
                  $q->where([
                                ['item_type_id', '01'],
                                ['unit_type_id', '!=', 'ZZ'],
                            ])
                    ->whereNotIsSet()
                    ->whereStockMinValidate();
              });
        }


        if ($warehouse_id != 0) {
            $query->where('item_warehouse.warehouse_id', $warehouse_id);
        }

        if ($request->category_id) $query->whereItemCategory($request->category_id);

        if ($request->brand_id) $query->whereItemBrand($request->brand_id);

        return $query;

    }

    public function downLoadTrayReport(Request $request){
        $tray = DownloadTray::create([
            'user_id' => auth()->user()->id,
            'module' => 'INVENTORY',
            'path' => $request->path,
            'format' => 'pdf',
            'type' => 'Reporte Inventario'
        ]);

        $company = Company::active();
        $client = Client::where('number', $company->number)->first();
        $website_id = $client->hostname->website_id;

        ProcessInventoryReport::dispatch($website_id, $tray->id)->onQueue('process_inventory_report');

        return  [
            'success' => true,
            'message' => 'El reporte se esta procesando; puede ver el proceso en bandeja de descargas.'
        ];
    }

    public function export(Request $request)
    {
        $host = $request->getHost();
        $tray = DownloadTray::create([
            'user_id' => auth()->user()->id,
            'module' => 'INVENTORY',
            'format' => $request->format,
            'date_init' => date('Y-m-d H:i:s'),
            'type' => 'Reporte Inventario'
        ]);
        $trayId = $tray->id;
        $hostname = Hostname::where('fqdn',$host)->first();
        if(empty($hostname)) {
            $company = Company::active();
            $number = $company->number;
            $client = Client::where('number', $number)->first();
            $website_id = $client->hostname->website_id;
        }else{
            $website_id = $hostname->website_id;
        }
        ProcessInventoryReport::dispatch($website_id,$trayId, ($request->warehouse_id == 'all' ? 0 :  $request->warehouse_id), $request->format, $request->all() );

        return  [
            'success' => true,
            'message' => 'El reporte se esta procesando; puede ver el proceso en bandeja de descargas.'
        ];
    }

    /**
     * Search
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {

        $reports = ItemWarehouse::with(['item'])->whereHas('item', function ($q) {
            $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']]);
            $q->whereNotIsSet();
        })->latest()->get();

        return view('inventory::reports.inventory.index', compact('reports'));
    }

    /**
     * PDF
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function pdf(Request $request)
    {

        $company = Company::first();
        $establishment = Establishment::first();
        ini_set('max_execution_time', 0);

        if ($request->warehouse_id && $request->warehouse_id != 'all') {
            $reports = ItemWarehouse::with(['item', 'item.brand'])->where('warehouse_id', $request->warehouse_id)->whereHas('item', function ($q) {
                $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']]);
                $q->whereNotIsSet();
            })->latest()->get();
        } else {

            $reports = ItemWarehouse::with(['item', 'item.brand'])->whereHas('item', function ($q) {
                $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']]);
                $q->whereNotIsSet();
            })->latest()->get();
        }


        $pdf = PDF::loadView('inventory::reports.inventory.report_pdf', compact("reports", "company", "establishment"));
        $pdf->setPaper('A4', 'landscape');
        $filename = 'Reporte_Inventario' . date('YmdHis');

        return $pdf->download($filename . '.pdf');
    }

    /**
     * Excel
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request)
    {
        $company = Company::first();
        $establishment = Establishment::first();


        if ($request->warehouse_id && $request->warehouse_id != 'all') {
            $records = ItemWarehouse::with(['item', 'item.brand'])->where('warehouse_id', $request->warehouse_id)->whereHas('item', function ($q) {
                $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']]);
                $q->whereNotIsSet();
            })->latest()->get();

        } else {
            $records = ItemWarehouse::with(['item', 'item.brand'])->whereHas('item', function ($q) {
                $q->where([['item_type_id', '01'], ['unit_type_id', '!=', 'ZZ']]);
                $q->whereNotIsSet();
            })->latest()->get();

        }


        return (new InventoryExport)
            ->records($records)
            ->company($company)
            ->establishment($establishment)
            ->download('ReporteInv' . Carbon::now() . '.xlsx');
    }
}
