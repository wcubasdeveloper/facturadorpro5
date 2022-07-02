<?php

    namespace App\Http\Controllers\Tenant;

    use App\CoreFacturalo\Helpers\Storage\StorageDocument;
    use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
    use App\CoreFacturalo\Template;
    use App\Http\Controllers\Controller;
    use App\Http\Controllers\SearchItemController;
    use App\Http\Requests\Tenant\PurchaseImportRequest;
    use App\Http\Requests\Tenant\PurchaseRequest;
    use App\Http\Resources\Tenant\PurchaseCollection;
    use App\Http\Resources\Tenant\PurchaseResource;
    use App\Models\Tenant\Catalogs\AffectationIgvType;
    use App\Models\Tenant\Catalogs\AttributeType;
    use App\Models\Tenant\Catalogs\ChargeDiscountType;
    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Catalogs\DocumentType;
    use App\Models\Tenant\Catalogs\OperationType;
    use App\Models\Tenant\Catalogs\PriceType;
    use App\Models\Tenant\Catalogs\SystemIscType;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\GuideFile;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\ItemUnitType;
    use App\Models\Tenant\ItemWarehouse;
    use App\Models\Tenant\PaymentMethodType;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\Purchase;
    use App\Models\Tenant\PurchaseItem;
    use App\Traits\OfflineTrait;
    use DOMDocument;
    use Exception;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Str;
    use Modules\Finance\Http\Controllers\PaymentFileController;
    use Modules\Finance\Traits\FinanceTrait;
    use Modules\Inventory\Models\Warehouse;
    use Modules\Item\Models\ItemLotsGroup;
    use Modules\Purchase\Models\PurchaseOrder;
    use Mpdf\Config\ConfigVariables;
    use Mpdf\Config\FontVariables;
    use Mpdf\HTMLParserMode;
    use Mpdf\Mpdf;
    use stdClass;
    use Symfony\Component\HttpFoundation\StreamedResponse;
    use Throwable;
    use App\Models\Tenant\GeneralPaymentCondition;


    class PurchaseController extends Controller
    {

        use FinanceTrait;
        use StorageDocument;
        use OfflineTrait;

        public function index()
        {
            return view('tenant.purchases.index');
        }


        public function create($purchase_order_id = null)
        {
            return view('tenant.purchases.form', compact('purchase_order_id'));
        }

        public function columns()
        {
            return [
                'number' => 'Número',
                'date_of_issue' => 'Fecha de emisión',
                'date_of_due' => 'Fecha de vencimiento',
                'date_of_payment' => 'Fecha de pago',
                'name' => 'Nombre proveedor',
            ];
        }

        public function records(Request $request)
        {

            $records = $this->getRecords($request);

            return new PurchaseCollection($records->paginate(config('tenant.items_per_page')));
        }

        public function getRecords($request)
        {

            switch ($request->column) {
                case 'name':

                    $records = Purchase::whereHas('supplier', function ($query) use ($request) {
                        return $query->where($request->column, 'like', "%{$request->value}%");
                    })
                        ->whereTypeUser()
                        ->latest();

                    break;

                case 'date_of_payment':

                    $records = Purchase::whereHas('purchase_payments', function ($query) use ($request) {
                        return $query->where($request->column, 'like', "%{$request->value}%");
                    })
                        ->whereTypeUser()
                        ->latest();

                    break;

                default:

                    $records = Purchase::where($request->column, 'like', "%{$request->value}%")
                        ->whereTypeUser()
                        ->latest();

                    break;
            }

            return $records;

        }

        public function tables()
        {
            $suppliers = $this->table('suppliers');
            $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
            $currency_types = CurrencyType::whereActive()->get();
            $document_types_invoice = DocumentType::DocumentsActiveToPurchase()->get();
            $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
            $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
            $company = Company::active();
            $payment_method_types = PaymentMethodType::getPaymentMethodTypes();
            // $payment_method_types = PaymentMethodType::all();
            $payment_destinations = $this->getPaymentDestinations();
            $customers = $this->getPersons('customers');
            $configuration = Configuration::first();
            $payment_conditions = GeneralPaymentCondition::get();
            $warehouses = Warehouse::get();

            return compact('suppliers', 'establishment', 'currency_types', 'discount_types', 'configuration', 'payment_conditions',
                'charge_types', 'document_types_invoice', 'company', 'payment_method_types', 'payment_destinations', 'customers', 'warehouses');
        }

        public function table($table)
        {
            switch ($table) {
                case 'suppliers':

                    $suppliers = Person::whereType('suppliers')->orderBy('name')->get()->transform(function ($row) {
                        return [
                            'id' => $row->id,
                            'description' => $row->number . ' - ' . $row->name,
                            'name' => $row->name,
                            'number' => $row->number,
                            'perception_agent' => (bool)$row->perception_agent,
                            'identity_document_type_id' => $row->identity_document_type_id,
                            'identity_document_type_code' => $row->identity_document_type->code
                        ];
                    });
                    return $suppliers;

                    break;

                case 'items':
                    return SearchItemController::getItemToPurchase();
                    return SearchItemController::getItemToPurchase()->transform(function ($row) {
                        /*
                                            $items = Item::whereNotIsSet()->whereIsActive()->orderBy('description')->take(20)->get(); //whereWarehouse()
                                        return collect($items)->transform(function($row) {
                                            */
                        /** @var Item $row */
                        $full_description = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;
                        return [
                            'id' => $row->id,
                            'item_code' => $row->item_code,
                            'full_description' => $full_description,
                            'description' => $row->description,
                            'currency_type_id' => $row->currency_type_id,
                            'currency_type_symbol' => $row->currency_type->symbol,
                            'sale_unit_price' => $row->sale_unit_price,
                            'purchase_unit_price' => $row->purchase_unit_price,
                            'unit_type_id' => $row->unit_type_id,
                            'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                            'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                            'purchase_has_igv' => (bool)$row->purchase_has_igv,
                            'has_perception' => (bool)$row->has_perception,
                            'lots_enabled' => (bool)$row->lots_enabled,
                            'percentage_perception' => $row->percentage_perception,
                            'item_unit_types' => collect($row->item_unit_types)->transform(function ($row) {
                                return [
                                    'id' => $row->id,
                                    'description' => "{$row->description}",
                                    'item_id' => $row->item_id,
                                    'unit_type_id' => $row->unit_type_id,
                                    'quantity_unit' => $row->quantity_unit,
                                    'price1' => $row->price1,
                                    'price2' => $row->price2,
                                    'price3' => $row->price3,
                                    'price_default' => $row->price_default,
                                ];
                            }),
                            'series_enabled' => (bool)$row->series_enabled,

                            // 'warehouses' => collect($row->warehouses)->transform(function($row) {
                            //     return [
                            //         'warehouse_id' => $row->warehouse->id,
                            //         'warehouse_description' => $row->warehouse->description,
                            //         'stock' => $row->stock,
                            //     ];
                            // })
                        ];
                    });
//                return $items;

                    break;
                default:

                    return [];

                    break;
            }
        }

        public function getPersons($type)
        {

            $persons = Person::whereType($type)->orderBy('name')->take(20)->get()->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->number . ' - ' . $row->name,
                    'name' => $row->name,
                    'number' => $row->number,
                    'identity_document_type_id' => $row->identity_document_type_id,
                ];
            });

            return $persons;

        }

        public function item_tables()
        {

            // $items = $this->table('items');
            $items = SearchItemController::getItemToPurchase();
            $categories = [];
            $affectation_igv_types = AffectationIgvType::whereActive()->get();
            $system_isc_types = SystemIscType::whereActive()->get();
            $price_types = PriceType::whereActive()->get();
            $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
            $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
            $attribute_types = AttributeType::whereActive()->orderByDescription()->get();
            $warehouses = Warehouse::all();

            $operation_types = OperationType::whereActive()->get();
            $is_client = $this->getIsClient();
            $configuration = Configuration::first();
            $configuration = $configuration->getCollectionData();

            return compact(
                'items',
                'categories',
                'affectation_igv_types',
                'system_isc_types',
                'price_types',
                'discount_types',
                'charge_types',
                'attribute_types',
                'warehouses',
                'operation_types',
                'is_client',
                'configuration'
            );
        }

        public function record($id)
        {

            $record = new PurchaseResource(Purchase::findOrFail($id));

            return $record;
        }

        public function edit($id)
        {
            $resourceId = $id;
            return view('tenant.purchases.form_edit', compact('resourceId'));
        }

        public function store(PurchaseRequest $request)
        {
            $data = self::convert($request);
            try {
                $purchase = DB::connection('tenant')->transaction(function () use ($data) {
                    $doc = Purchase::create($data);
                    foreach ($data['items'] as $row) {
                        $p_item = new PurchaseItem();
                        $p_item->fill($row);
                        $lots = $row['lots'] ?? null;
                        if ($lots != null) {
                            // en compras, se guardan los lotes si existen en el campo item de purchase_items
                            $temp_item = $row['item'];
                            $temp_item['lots'] = $lots;
                            $p_item->item = $temp_item;
                        }
                        $p_item->purchase_id = $doc->id;
                        $p_item->save();

                        if (isset($row['update_price']) && $row['update_price']) {
                            if (!($row['sale_unit_price'] ?? false)) {
                                throw new Exception('Debe ingresar el nuevo precio de venta del producto, cuando la opción "Actualizar precio de venta" está activado', 500);
                            }
                            Item::where('id', $row['item_id'])
                                ->update(['sale_unit_price' => floatval($row['sale_unit_price'])]);
                        }

                        if (isset($row['update_purchase_price']) && $row['update_purchase_price']) {
                            Item::query()->where('id', $row['item_id'])
                                ->update(['purchase_unit_price' => floatval($row['unit_price'])]);
                            // actualizacion de precios
                            $item = $row['item'];
                            if (isset($item['item_unit_types'])) {
                                $unit_type = $item['item_unit_types'];
                                foreach ($unit_type as $value) {
                                    $item_unit_type = ItemUnitType::firstOrNew(['id' => $value['id']]);
                                    $item_unit_type->item_id = (int)$row['item_id'];
                                    $item_unit_type->description = $value['description'];
                                    $item_unit_type->unit_type_id = $value['unit_type_id'];
                                    $item_unit_type->quantity_unit = $value['quantity_unit'];
                                    $item_unit_type->price1 = $value['price1'];
                                    $item_unit_type->price2 = $value['price2'];
                                    $item_unit_type->price3 = $value['price3'];
                                    $item_unit_type->price_default = $value['price_default'];
                                    $item_unit_type->save();
                                }
                            }
                            if (isset($item['item_warehouse_prices'])) {
                                $warehouse_prices = $item['item_warehouse_prices'];
                                foreach ($warehouse_prices as $prices) {
                                    Item::setStaticItemWarehousePrice(
                                        (int)$row['item_id'],
                                        (int)$prices['id'],
                                        (int)$prices['warehouse_id'],
                                        $prices['price']
                                    );
                                }
                            }

                        }

                        if (isset($row['update_date_of_due'], $row['date_of_due']) && $row['update_date_of_due'] && !empty($row['date_of_due'])) {
                            $item_id = (int)$row['item_id'];
                            $it = Item::find($item_id);
                            if ($it != null) {
                                $it->date_of_due = $row['date_of_due'];
                                $it->push();
                            }
                        }

                        if (array_key_exists('lots', $row)) {

                            foreach ($row['lots'] as $lot) {

                                $p_item->lots()->create([
                                    'date' => $lot['date'],
                                    'series' => $lot['series'],
                                    'item_id' => $row['item_id'],
                                    'warehouse_id' => $row['warehouse_id'],
                                    'has_sale' => false,
                                    'state' => $lot['state']
                                ]);

                            }
                        }

                        if (array_key_exists('item', $row)) {
                            if (isset($row['item']['lots_enabled']) && $row['item']['lots_enabled'] == true) {

                                // factor de lista de precios
                                $presentation_quantity = (isset($p_item->item->presentation->quantity_unit)) ? $p_item->item->presentation->quantity_unit : 1;

                                ItemLotsGroup::create([
                                    'code' => $row['lot_code'],
                                    'quantity' => $row['quantity'] * $presentation_quantity,
                                    // 'quantity' => $row['quantity'],
                                    'date_of_due' => $row['date_of_due'],
                                    'item_id' => $row['item_id']
                                ]);

                            }
                        }

                    }

                    foreach ($data['payments'] as $payment) {

                        $record_payment = $doc->purchase_payments()->create($payment);

                        if (isset($payment['payment_destination_id'])) {
                            $this->createGlobalPayment($record_payment, $payment);
                        }
                    }

                    $this->savePurchaseFee($doc, $data['fee']);

                    $this->setFilename($doc);
                    $this->createPdf($doc, "a4", $doc->filename);

                    return $doc;
                });

                return [
                    'success' => true,
                    'data' => [
                        'id' => $purchase->id,
                        'number_full' => "{$purchase->series}-{$purchase->number}",
                    ],
                ];
            } catch (Throwable $th) {
                return response()->json([
                    'success' => false,
                    'message' => $th->getMessage(),
                ], 500);
            }
        }


        private function savePurchaseFee($purchase, $fee)
        {
            foreach ($fee as $row) {
                $purchase->fee()->create($row);
            }
        }

        public static function convert($inputs)
        {
            $company = Company::active();
            $values = [
                'user_id' => auth()->id(),
                'external_id' => Str::uuid()->toString(),
                'supplier' => PersonInput::set($inputs['supplier_id']),
                'soap_type_id' => $company->soap_type_id,
                'group_id' => ($inputs->document_type_id === '01') ? '01' : '02',
                'state_type_id' => '01'
            ];

            $inputs->merge($values);

            return $inputs->all();
        }

        private function setFilename($purchase)
        {

            $name = [$purchase->series, $purchase->number, $purchase->id, date('Ymd')];
            $purchase->filename = join('-', $name);
            $purchase->save();

        }

        /*public static function deleteLotsSerie($records)
        {
            foreach ($records as $row) {

                $it = ItemLot::findOrFail($row->id);
                $it->delete();
            }
        }*/

        public function createPdf($purchase = null, $format_pdf = null, $filename = null)
        {

            ini_set("pcre.backtrack_limit", "5000000");
            $template = new Template();
            $pdf = new Mpdf();

            $document = ($purchase != null) ? $purchase : $this->purchase;
            $company = Company::active();
            $filename = ($filename != null) ? $filename : $this->purchase->filename;

            $base_template = Establishment::find($document->establishment_id)->template_pdf;

            $html = $template->pdf($base_template, "purchase", $company, $document, $format_pdf);


            $pdf_font_regular = config('tenant.pdf_name_regular');
            $pdf_font_bold = config('tenant.pdf_name_bold');

            if ($pdf_font_regular != false) {
                $defaultConfig = (new ConfigVariables())->getDefaults();
                $fontDirs = $defaultConfig['fontDir'];

                $defaultFontConfig = (new FontVariables())->getDefaults();
                $fontData = $defaultFontConfig['fontdata'];

                $pdf = new Mpdf([
                    'fontDir' => array_merge($fontDirs, [
                        app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
                            DIRECTORY_SEPARATOR . 'pdf' .
                            DIRECTORY_SEPARATOR . $base_template .
                            DIRECTORY_SEPARATOR . 'font')
                    ]),
                    'fontdata' => $fontData + [
                            'custom_bold' => [
                                'R' => $pdf_font_bold . '.ttf',
                            ],
                            'custom_regular' => [
                                'R' => $pdf_font_regular . '.ttf',
                            ],
                        ]
                ]);
            }

            $path_css = app_path('CoreFacturalo' . DIRECTORY_SEPARATOR . 'Templates' .
                DIRECTORY_SEPARATOR . 'pdf' .
                DIRECTORY_SEPARATOR . $base_template .
                DIRECTORY_SEPARATOR . 'style.css');

            $stylesheet = file_get_contents($path_css);

            $pdf->WriteHTML($stylesheet, HTMLParserMode::HEADER_CSS);
            $pdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

            if ($format_pdf != 'ticket') {
                if (config('tenant.pdf_template_footer')) {
                    $html_footer = $template->pdfFooter($base_template, $document);
                    $pdf->SetHTMLFooter($html_footer);
                }
            }

            $this->uploadFile($filename, $pdf->output('', 'S'), 'purchase');
        }

        public function uploadFile($filename, $file_content, $file_type)
        {
            $this->uploadStorage($filename, $file_content, $file_type);
        }

        public function toPrint($external_id, $format)
        {
            $purchase = Purchase::where('external_id', $external_id)->first();

            if (!$purchase) throw new Exception("El código {$external_id} es inválido, no se encontro el pedido relacionado");

            $this->reloadPDF($purchase, $format, $purchase->filename);
            $temp = tempnam(sys_get_temp_dir(), 'purchase');

            file_put_contents($temp, $this->getStorage($purchase->filename, 'purchase'));

            return response()->file($temp);
        }

        private function reloadPDF($purchase, $format, $filename)
        {
            $this->createPdf($purchase, $format, $filename);
        }

        public function update(PurchaseRequest $request)
        {

            $purchase = DB::connection('tenant')->transaction(function () use ($request) {

                $doc = Purchase::firstOrNew(['id' => $request['id']]);
                $doc->fill($request->all());
                $doc->supplier = PersonInput::set($request['supplier_id']);
                $doc->group_id = ($request->document_type_id === '01') ? '01' : '02';
                $doc->user_id = auth()->id();
                $doc->save();

                foreach ($doc->items as $it) {

                    $p_i = PurchaseItem::findOrFail($it->id);
                    $p_i->delete();

                }

                foreach ($request['items'] as $row) {
                    $p_item = new PurchaseItem();
                    $p_item->fill($row);
                    $p_item->purchase_id = $doc->id;
                    $p_item->save();

                    if (array_key_exists('lots', $row)) {

                        foreach ($row['lots'] as $lot) {

                            $p_item->lots()->create([
                                'date' => $lot['date'],
                                'series' => $lot['series'],
                                'item_id' => $row['item_id'],
                                'warehouse_id' => $row['warehouse_id'],
                                'has_sale' => false
                            ]);

                        }
                    }

                    if (array_key_exists('item', $row)) {
                        if (isset($row['item']['lots_enabled']) && $row['item']['lots_enabled'] == true) {

                            // factor de lista de precios
                            $presentation_quantity = (isset($p_item->item->presentation->quantity_unit)) ? $p_item->item->presentation->quantity_unit : 1;

                            ItemLotsGroup::create([
                                'code' => $row['lot_code'],
                                'quantity' => $row['quantity'] * $presentation_quantity,
                                // 'quantity' => $row['quantity'],
                                'date_of_due' => $row['date_of_due'],
                                'item_id' => $row['item_id']
                            ]);

                        }
                    }
                }

                $this->deleteAllPayments($doc->purchase_payments);

                foreach ($request['payments'] as $payment) {

                    $record_payment = $doc->purchase_payments()->create($payment);

                    if (isset($payment['payment_destination_id'])) {
                        $this->createGlobalPayment($record_payment, $payment);
                    }

                    if (isset($payment['payment_filename'])) {
                        $record_payment->payment_file()->create([
                            'filename' => $payment['payment_filename']
                        ]);
                    }
                }

                $doc->fee()->delete();
                $this->savePurchaseFee($doc, $request['fee']);


                if (!$doc->filename) {
                    $this->setFilename($doc);
                }
                $this->createPdf($doc, "a4", $doc->filename);

                return $doc;
            });

            return [
                'success' => true,
                'data' => [
                    'id' => $purchase->id,
                ],
            ];

        }

        /**
         * @param Request $request
         *
         * @return array
         */
        public function uploadAttached(Request $request)
        {
            $paymentController = new PaymentFileController();
            return $paymentController->uploadAttached($request);
        }

        /**
         * Busca el archivo basado el el id de compra y el nombre del archivo
         *
         * @param Purchase $purchase
         * @param          $filename
         *
         * @return StreamedResponse
         * @throws Exception
         */
        public function downloadGuide(Purchase $purchase, $filename)
        {
            $guideFile = GuideFile::where([
                'purchase_id' => $purchase->id,
                'filename' => $filename
            ])->first();
            if (!empty($guideFile)) return $guideFile->download();

            throw new Exception("El registro no fue encontrado.");

        }

        /**
         * Se utiliza para consultar los datos de compra para guias. Si updateGuide existe
         * se utiliza para guardar los datos de guia.
         *
         * @param Request       $request
         * @param Purchase|null $purchase
         *
         * @return array
         */
        public function processGuides(Request $request, Purchase $purchase = null)
        {

            if ($request->has('updateGuide') && $request->has('guides')) {
                $guides = [];
                foreach ($request->guides as $guide) {
                    if (!empty($guide['number'])) {
                        if(isset($guide['live'])) unset($guide['live']);
                        $guides[] = $guide;
                    }
                }
                $purchase->setGuidesAttribute($guides);
                $purchase->push();
                $ids = [];
                foreach ($purchase->getGuides() as $guide) {
                    /** @var stdClass $guide */
                    if (property_exists($guide, 'filename')) {
                        $toSearch = [
                            'purchase_id' => $purchase->id,
                            'filename' => $guide->filename
                        ];
                        // Busca o crea los archivos de guia
                        $guideFile = GuideFile::where($toSearch)->first();
                        if ($guideFile == null) $guideFile = new GuideFile($toSearch);
                        $guideFile->push();
                        $ids[] = $guideFile->id;
                        $guideFile->saveFiles($guide->temp_path);
                    }
                }
                // Borra las guias que no existan para la compra correspondiente
                GuideFile::wherenotin('id', $ids)->where('purchase_id', $purchase->id)->get()->transform(function($item){
                    $item->delete();
                });
            }
            return $purchase->getCollectionData();
        }

        public function anular($id)
        {
            $obj = Purchase::find($id);
            $validated = self::verifyHasSaleItems($obj->items);
            if (!$validated['success']) {
                return [
                    'success' => false,
                    'message' => $validated['message']
                ];
            }

            DB::connection('tenant')->transaction(function () use ($obj) {

                foreach ($obj->items as $it) {
                    $it->lots()->delete();
                }

                $obj->state_type_id = 11;
                $obj->save();

                foreach ($obj->items as $item) {
                    $item->purchase->inventory_kardex()->create([
                        'date_of_issue' => date('Y-m-d'),
                        'item_id' => $item->item_id,
                        'warehouse_id' => $item->warehouse_id,
                        'quantity' => -$item->quantity,
                    ]);
                    $wr = ItemWarehouse::where([['item_id', $item->item_id], ['warehouse_id', $item->warehouse_id]])->first();
                    $wr->stock = $wr->stock - $item->quantity;
                    $wr->save();
                }

            });

            return [
                'success' => true,
                'message' => 'Compra anulada con éxito'
            ];
        }

        public static function verifyHasSaleItems($items)
        {
            $validated = true;
            $message = '';
            foreach ($items as $element) {

                $lot_has_sale = collect($element->lots)->firstWhere('has_sale', 1);
                if ($lot_has_sale) {
                    $validated = false;
                    $message = 'No se puede anular esta compra, series en productos no disponibles';
                    break;
                }
                $lot_enabled = false;
                if (is_array($element->item)) {
                    if (in_array('lots_enabled', $element->item)) {
                        $lot_enabled = true;
                    }
                } elseif (is_object($element->item)) {
                    if (property_exists($element->item, 'lots_enabled')) {
                        $lot_enabled = true;
                    }
                }
                if ($lot_enabled) {
                    if ($element->item->lots_enabled && $element->lot_code) {
                        $lot_group = ItemLotsGroup::where('code', $element->lot_code)->first();

                        if (!$lot_group) {
                            $message = "Lote {$element->lot_code} no encontrado.";
                            $validated = false;
                            break;
                        }

                        if ((int)$lot_group->quantity != (int)$element->quantity) {
                            $message = "Los productos del lote {$element->lot_code} han sido vendidos!";
                            $validated = false;
                            break;
                        }
                    }
                }
            }

            return [
                'success' => $validated,
                'message' => $message
            ];


        }

        public function searchItemById($id)
        {


            $items = SearchItemController::getItemToPurchase(null, $id);
            $a = null;
            // Solo para que no entre en esta seccion
            if ($a !== null) {
                $items = SearchItemController::getNotServiceItemToPurchase(null, $id)->transform(function ($row) {
                    /** @var Item $row */
                    $full_description = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;
                    return [
                        'id' => $row->id,
                        'item_code' => $row->item_code,
                        'full_description' => $full_description,
                        'description' => $row->description,
                        'currency_type_id' => $row->currency_type_id,
                        'currency_type_symbol' => $row->currency_type->symbol,
                        'sale_unit_price' => $row->sale_unit_price,
                        'purchase_unit_price' => $row->purchase_unit_price,
                        'unit_type_id' => $row->unit_type_id,
                        'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                        'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                        'purchase_has_igv' => (bool)$row->purchase_has_igv,
                        'has_perception' => (bool)$row->has_perception,
                        'lots_enabled' => (bool)$row->lots_enabled,
                        'percentage_perception' => $row->percentage_perception,
                        'item_unit_types' => collect($row->item_unit_types)->transform(function ($row) {
                            return [
                                'id' => $row->id,
                                'description' => "{$row->description}",
                                'item_id' => $row->item_id,
                                'unit_type_id' => $row->unit_type_id,
                                'quantity_unit' => $row->quantity_unit,
                                'price1' => $row->price1,
                                'price2' => $row->price2,
                                'price3' => $row->price3,
                                'price_default' => $row->price_default,
                            ];
                        }),
                        'series_enabled' => (bool)$row->series_enabled,
                    ];
                });
            }
            return compact('items');
        }

        public function searchItems(Request $request)
        {
            $items = SearchItemController::getItemToPurchase($request);
            // Solo para evitar que entre en esta seccion
            $a = null;
            if ($a != null) {
                $items = SearchItemController::getItemToPurchase($request)->transform(function ($row) {
                    /** @var Item $row */
                    $full_description = ($row->internal_id) ? $row->internal_id . ' - ' . $row->description : $row->description;
                    $temp = array_merge($row->getCollectionData(), $row->getDataToItemModal());
                    $data = [
                        'id' => $row->id,
                        'item_code' => $row->item_code,
                        'full_description' => $full_description,
                        'description' => $row->description,
                        'currency_type_id' => $row->currency_type_id,
                        'currency_type_symbol' => $row->currency_type->symbol,
                        'sale_unit_price' => $row->sale_unit_price,
                        'purchase_unit_price' => $row->purchase_unit_price,
                        'unit_type_id' => $row->unit_type_id,
                        'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                        'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                        'purchase_has_igv' => (bool)$row->purchase_has_igv,
                        'has_perception' => (bool)$row->has_perception,
                        'lots_enabled' => (bool)$row->lots_enabled,
                        'percentage_perception' => $row->percentage_perception,
                        'item_unit_types' => $row->item_unit_types->transform(function ($row) {
                            if (is_array($row)) return $row;
                            if (is_object($row)) {
                                /**@var ItemUnitType $row */
                                return $row->getCollectionData();
                            }
                            return $row;
                            return [
                                'id' => $row->id,
                                'description' => "{$row->description}",
                                'item_id' => $row->item_id,
                                'unit_type_id' => $row->unit_type_id,
                                'quantity_unit' => $row->quantity_unit,
                                'price1' => $row->price1,
                                'price2' => $row->price2,
                                'price3' => $row->price3,
                                'price_default' => $row->price_default,
                            ];
                        }),
                        'series_enabled' => (bool)$row->series_enabled,
                    ];
                    foreach ($temp as $k => $v) {
                        if (!isset($data[$k])) {
                            $data[$k] = $v;
                        }
                    }
                    return $data;
                });
            }
            return compact('items');

        }

        public function delete($id)
        {

            try {

                DB::connection('tenant')->transaction(function () use ($id) {

                    $row = Purchase::findOrFail($id);
                    $this->deleteAllPayments($row->purchase_payments);
                    $row->delete();

                });

                return [
                    'success' => true,
                    'message' => 'Compra eliminada con éxito'
                ];

            } catch (Exception $e) {

                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }
        }

        public function xml2array($xmlObject, $out = [])
        {
            foreach ((array)$xmlObject as $index => $node) {
                $out[$index] = (is_object($node)) ? $this->xml2array($node) : $node;
            }
            return $out;
        }

        public function XMLtoArray($xml)
        {
            $previous_value = libxml_use_internal_errors(true);
            $dom = new DOMDocument('1.0', 'UTF-8');
            $dom->preserveWhiteSpace = false;
            $dom->loadXml($xml);
            libxml_use_internal_errors($previous_value);
            if (libxml_get_errors()) {
                return [];
            }
            return $this->DOMtoArray($dom);
        }

        public function DOMtoArray($root)
        {
            $result = [];

            if ($root->hasAttributes()) {
                $attrs = $root->attributes;
                foreach ($attrs as $attr) {
                    $result['@attributes'][$attr->name] = $attr->value;
                }
            }

            if ($root->hasChildNodes()) {
                $children = $root->childNodes;
                if ($children->length == 1) {
                    $child = $children->item(0);
                    if (in_array($child->nodeType, [XML_TEXT_NODE, XML_CDATA_SECTION_NODE])) {
                        $result['_value'] = $child->nodeValue;
                        return count($result) == 1
                            ? $result['_value']
                            : $result;
                    }

                }
                $groups = [];
                foreach ($children as $child) {
                    if (!isset($result[$child->nodeName])) {
                        $result[$child->nodeName] = $this->DOMtoArray($child);
                    } else {
                        if (!isset($groups[$child->nodeName])) {
                            $result[$child->nodeName] = [$result[$child->nodeName]];
                            $groups[$child->nodeName] = 1;
                        }
                        $result[$child->nodeName][] = $this->DOMtoArray($child);
                    }
                }
            }
            return $result;
        }

        /*public function itemResource($id)
        {
            $establishment_id = auth()->user()->establishment_id;
            $warehouse = Warehouse::where('establishment_id', $establishment_id)->first();
            $row = Item::find($id);
            return [
                'id' => $row->id,
                'description' => $row->description,
                'lots' => $row->item_lots->where('has_sale', false)->where('warehouse_id', $warehouse->id)->transform(function($row) {
                    return [
                        'id' => $row->id,
                        'series' => $row->series,
                        'date' => $row->date,
                        'item_id' => $row->item_id,
                        'warehouse_id' => $row->warehouse_id,
                        'has_sale' => (bool)$row->has_sale,
                        'lot_code' => ($row->item_loteable_type) ? (isset($row->item_loteable->lot_code) ? $row->item_loteable->lot_code:null):null
                    ];
                })->values(),
                'series_enabled' => (bool) $row->series_enabled,
            ];
        }*/

        public function import(PurchaseImportRequest $request)
        {
            try {
                $model = $request->all();
                $supplier = Person::whereType('suppliers')->where('number', $model['supplier_ruc'])->first();
                if (!$supplier) {
                    return [
                        'success' => false,
                        'data' => 'Supplier not exist.',
                        'message' => 'Supplier not exist.'
                    ];
                }
                $model['supplier_id'] = $supplier->id;
                $company = Company::active();
                $values = [
                    'user_id' => auth()->id(),
                    'external_id' => Str::uuid()->toString(),
                    'supplier' => PersonInput::set($model['supplier_id']),
                    'soap_type_id' => $company['soap_type_id'],
                    'group_id' => ($model['document_type_id'] === '01') ? '01' : '02',
                    'state_type_id' => '01'
                ];

                $data = array_merge($model, $values);

                $purchase = DB::connection('tenant')->transaction(function () use ($data) {
                    $doc = Purchase::create($data);
                    foreach ($data['items'] as $row) {
                        $doc->items()->create($row);
                    }

                    $doc->purchase_payments()->create([
                        'date_of_payment' => $data['date_of_issue'],
                        'payment_method_type_id' => $data['payment_method_type_id'],
                        'payment' => $data['total'],
                    ]);

                    return $doc;
                });

                return [
                    'success' => true,
                    'message' => 'Xml cargado correctamente.',
                    'data' => [
                        'id' => $purchase->id,
                    ],
                ];


            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }

        }

        public function destroy_purchase_item($id)
        {

            DB::connection('tenant')->transaction(function () use ($id) {

                $item = PurchaseItem::findOrFail($id);
                $item->delete();

            });

            return [
                'success' => true,
                'message' => 'Item eliminado'
            ];
        }

        public function download($external_id, $format = 'a4')
        {
            $purchase = SaleOpportunity::where('external_id', $external_id)->first();

            if (!$purchase) throw new Exception("El código {$external_id} es inválido, no se encontro el archivo relacionado");

            return $this->downloadStorage($purchase->filename, 'purchase');
        }


        public function searchPurchaseOrder(Request $request){
            // $input = (string)$request->input;
            $purchases = Purchase::select('purchase_order_id')->wherenotnull('purchase_order_id')
                ->get()
                ->pluck('purchase_order_id');
            $purchaseOrder = PurchaseOrder::whereNotIn('id',$purchases)
                // ->where('prefix','like','%'.$input.'%')
                ->get()
            ->transform(function(PurchaseOrder $row){
                $data =[
                    'id'=>$row->id,
                    'description'=>$row->getNumberFullAttribute(),
                ];
                return $data;
            });
            return $purchaseOrder;
        }
    }

