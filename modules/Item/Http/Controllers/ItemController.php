<?php

    namespace Modules\Item\Http\Controllers;

    use \Exception;
    use App\Models\Tenant\DocumentItem;
    use App\Models\Tenant\Document;
    use App\Models\Tenant\SaleNote;
    use App\Models\Tenant\Quotation;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\PurchaseItem;
    use App\Models\Tenant\SaleNoteItem;
    use App\Models\Tenant\QuotationItem;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Facades\DB;
    use Maatwebsite\Excel\Excel;
    use Modules\Item\Http\Resources\ItemHistoryPurchasesCollection;
    use Modules\Item\Http\Resources\ItemHistorySalesCollection;
    use Modules\Item\Http\Resources\ItemLotCollection;
    use Modules\Item\Imports\ItemListPriceImport;
    use Modules\Item\Imports\ItemListWithExtraData;
    use Modules\Item\Models\ItemLot;
    use Picqer\Barcode\BarcodeGeneratorPNG;
    use Illuminate\Support\Carbon;
    use Modules\Item\Imports\{
        ItemUpdatePriceImport
    };

    
    class ItemController extends Controller
    {

        public function generateBarcode($id)
        {

            $item = Item::findOrFail($id);

            $colour = [150, 150, 150];

            $generator = new BarcodeGeneratorPNG();

            $temp = tempnam(sys_get_temp_dir(), 'item_barcode');

            file_put_contents($temp, $generator->getBarcode($item->barcode, $generator::TYPE_CODE_128, 5, 70, $colour));

            $headers = [
                'Content-Type' => 'application/png',
            ];

            return response()->download($temp, "{$item->barcode}.png", $headers);

        }


        public function importItemPriceLists(Request $request)
        {
            if ($request->hasFile('file')) {
                try {
                    $import = new ItemListPriceImport();
                    $import->import($request->file('file'), null, Excel::XLSX);
                    $data = $import->getData();
                    return [
                        'success' => true,
                        'message' => __('app.actions.upload.success'),
                        'data' => $data
                    ];
                } catch (Exception $e) {
                    return [
                        'success' => false,
                        'message' => $e->getMessage()
                    ];
                }
            }
            return [
                'success' => false,
                'message' => __('app.actions.upload.error'),
            ];
        }

        /**
         * @param Request $request
         *
         * @return array
         */
        public function importItemWithExtraData(Request $request): array
        {
            if ($request->hasFile('file')) {
                try {
                    $import = new ItemListWithExtraData();
                    $import->import($request->file('file'), null, Excel::XLSX);
                    $data = $import->getData();
                    return [
                        'success' => true,
                        'message' => __('app.actions.upload.success'),
                        'data' => $data
                    ];
                } catch (Exception $e) {
                    return [
                        'success' => false,
                        'message' => $e->getMessage()
                    ];
                }
            }
            return [
                'success' => false,
                'message' => __('app.actions.upload.error'),
            ];
        }

        public function getDataHistory($id)
        {

            $item = Item::findOrFail($id);

            return [
                'warehouses' => $item->warehouses->transform(function ($row) use ($item) {
                    return [
                        'warehouse_id' => $row->warehouse->id,
                        'warehouse_description' => $row->warehouse->description,
                        'stock' => $row->stock,
                        'item_id' => $item->id,
                        'series_enabled' => (bool)$item->series_enabled,
                    ];
                })
            ];

        }


        public function availableSeriesRecords(Request $request)
        {

            $form = json_decode($request->form);

            $records = ItemLot::where('has_sale', false)
                ->where('item_id', $form->item_id)
                ->where('warehouse_id', $form->warehouse_id)
                ->latest();

            return new ItemLotCollection($records->paginate(config('tenant.items_per_page_simple_d_table_params')));

        }


        public function itemHistorySales(Request $request)
        {

            $form = json_decode($request->form);

            $sale_notes = SaleNoteItem::where('item_id', $form->item_id)
                ->join('sale_notes', 'sale_note_items.sale_note_id', '=', 'sale_notes.id')
                ->join('persons', 'sale_notes.customer_id', '=', 'persons.id')
                ->select(DB::raw("sale_note_items.id as id, sale_notes.series as series, sale_notes.number as number,
                                            sale_note_items.unit_price as price, sale_notes.date_of_issue as date_of_issue, sale_notes.total as total,
                                            persons.number as customer_number, persons.name as customer_name, sale_note_items.quantity as quantity,
                                            sale_notes.created_at as created_at"));

            $documents = DocumentItem::where('item_id', $form->item_id)
                ->join('documents', 'document_items.document_id', '=', 'documents.id')
                ->join('persons', 'documents.customer_id', '=', 'persons.id')
                ->select(DB::raw("document_items.id as id, documents.series as series, documents.number as number,
                                            document_items.unit_price as price, documents.date_of_issue as date_of_issue, documents.total as total,
                                            persons.number as customer_number, persons.name as customer_name, document_items.quantity as quantity,
                                            documents.created_at as created_at"));

            return new ItemHistorySalesCollection($documents->union($sale_notes)->orderBy('created_at', 'desc')->paginate(config('tenant.items_per_page_simple_d_table_params')));

        }


        public function itemHistoryPurchases(Request $request)
        {

            $form = json_decode($request->form);

            $purchases = PurchaseItem::where('item_id', $form->item_id)
                ->join('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
                ->join('persons', 'purchases.supplier_id', '=', 'persons.id')
                ->select(DB::raw("purchase_items.id as id, purchases.series as series, purchases.number as number,
                                    purchases.supplier as supplier,purchase_items.unit_price as price, purchases.date_of_issue as date_of_issue, purchases.total as total,
                                    persons.number as supplier_number, persons.name as supplier_name, purchase_items.quantity as quantity,
                                    purchases.created_at as created_at"));


            return new ItemHistoryPurchasesCollection($purchases->orderBy('created_at', 'desc')->paginate(config('tenant.items_per_page_simple_d_table_params')));

        }

        public function itemtLastSale(Request $request) {
            
            $type_document = $request->type_document;
            $customer_id = $request->customer_id;
            $item_id = $request->item_id;

            $item = null;
            if($type_document == 'CPE') {

                $item = DocumentItem::whereHas('document', function ($query) use ($customer_id) {
                    $query->where('customer_id', $customer_id);
                })->orderBy('id', 'desc')->where('item_id', $item_id)->first();

            }
            else if($type_document == 'NV') {

                $item = SaleNoteItem::whereHas('sale_note', function ($query) use ($customer_id) {
                    $query->where('customer_id', $customer_id);
                })->orderBy('id', 'desc')->where('item_id', $item_id)->first();

            }
            else  if($type_document == 'QUOTATION') {

                $document_cpe_item = DocumentItem::whereHas('document', function ($query) use ($customer_id) {
                    $query->where('customer_id', $customer_id);
                })->orderBy('id', 'desc')->where('item_id', $item_id)->first();
                

                $sale_note_item = SaleNoteItem::whereHas('sale_note', function ($query) use ($customer_id) {
                    $query->where('customer_id', $customer_id);
                })->orderBy('id', 'desc')->where('item_id', $item_id)->first();

                if($document_cpe_item && $sale_note_item) {

                    if(Carbon::parse($document_cpe_item->document->created_at)->gte(Carbon::parse($sale_note_item->sale_note->created_at)) ){
                        $item = $document_cpe_item;
                    }
                    else {
                        $item = $sale_note_item;
                    }
                }
                else {
                    if ($document_cpe_item) {
                        $item = $document_cpe_item;
                    }elseif($sale_note_item){
                        $item = $sale_note_item;
                    }
                }
            }

            return [
                'unit_price' => $item ? $item->unit_price: null,
                'item_id' => $item ? $item->id: null,
            ];

        }

        /**
         * 
         * Importar excel para actualizar los precios de forma masiva
         * 
         * @param Request $request
         *
         * @return array
         */
        public function importItemUpdatePrices(Request $request)
        {
            if ($request->hasFile('file')) {
                try {
                    $import = new ItemUpdatePriceImport();
                    $import->import($request->file('file'), null, Excel::XLSX);
                    $data = $import->getData();
                    return [
                        'success' => true,
                        'message' => __('app.actions.upload.success'),
                        'data' => $data
                    ];
                } catch (Exception $e) {
                    return [
                        'success' => false,
                        'message' => $e->getMessage()
                    ];
                }
            }
            return [
                'success' => false,
                'message' => __('app.actions.upload.error'),
            ];
        }


    }
