<?php
namespace App\Http\Controllers\Tenant;

use App\CoreFacturalo\Facturalo;
use App\CoreFacturalo\Helpers\Storage\StorageDocument;
use App\CoreFacturalo\Helpers\Template\ReportHelper;
use App\Exports\PaymentExport;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SearchItemController;
use App\Http\Requests\Tenant\DocumentEmailRequest;
use App\Http\Requests\Tenant\DocumentRequest;
use App\Http\Requests\Tenant\DocumentUpdateRequest;
use App\Http\Resources\Tenant\DocumentCollection;
use App\Http\Resources\Tenant\DocumentResource;
use App\Imports\DocumentsImport;
use App\Imports\DocumentsImportTwoFormat;
use App\Mail\Tenant\DocumentEmail;
use App\Models\Tenant\Catalogs\AffectationIgvType;
use App\Models\Tenant\Catalogs\AttributeType;
use App\Models\Tenant\Catalogs\CatColorsItem;
use App\Models\Tenant\Catalogs\CatItemMoldCavity;
use App\Models\Tenant\Catalogs\CatItemMoldProperty;
use App\Models\Tenant\Catalogs\CatItemPackageMeasurement;
use App\Models\Tenant\Catalogs\CatItemProductFamily;
use App\Models\Tenant\Catalogs\CatItemStatus;
use App\Models\Tenant\Catalogs\CatItemUnitBusiness;
use App\Models\Tenant\Catalogs\CatItemUnitsPerPackage;
use App\Models\Tenant\Catalogs\ChargeDiscountType;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Catalogs\NoteCreditType;
use App\Models\Tenant\Catalogs\NoteDebitType;
use App\Models\Tenant\Catalogs\OperationType;
use App\Models\Tenant\Catalogs\PriceType;
use App\Models\Tenant\Catalogs\SystemIscType;
use App\Models\Tenant\CatItemSize;
use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Dispatch;
use App\Models\Tenant\Document;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Item;
use App\Models\Tenant\PaymentCondition;
use App\Models\Tenant\PaymentMethodType;
use App\Models\Tenant\Person;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\Series;
use App\Models\Tenant\StateType;
use App\Models\Tenant\User;
use App\Traits\OfflineTrait;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use Modules\BusinessTurn\Models\BusinessTurn;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Inventory\Models\Warehouse as ModuleWarehouse;
use Modules\Item\Http\Requests\BrandRequest;
use Modules\Item\Http\Requests\CategoryRequest;
use Modules\Item\Models\Brand;
use Modules\Item\Models\Category;
use Modules\Document\Helpers\DocumentHelper;


class DocumentController extends Controller
{
    use FinanceTrait;
    use OfflineTrait;
    use StorageDocument;

    private $max_count_payment = 0;

    public function __construct()
    {
        $this->middleware('input.request:document,web', ['only' => ['store']]);
        $this->middleware('input.request:documentUpdate,web', ['only' => ['update']]);
    }

    public function index()
    {
        $is_client = $this->getIsClient();
        $import_documents = config('tenant.import_documents');
        $import_documents_second = config('tenant.import_documents_second_format');
        $configuration = Configuration::getPublicConfig();

        // apiperu
        // se valida cual api usar para validacion desde el listado de comprobantes
        $view_apiperudev_validator_cpe = config('tenant.apiperudev_validator_cpe');
        $view_validator_cpe = config('tenant.validator_cpe');

        return view('tenant.documents.index',
            compact('is_client','import_documents',
                'import_documents_second',
                'configuration',
                'view_apiperudev_validator_cpe',
                'view_validator_cpe'));
    }

    public function columns()
    {
        return [
            'number' => 'Número',
            'date_of_issue' => 'Fecha de emisión'
        ];
    }

    public function records(Request $request)
    {

        $records = $this->getRecords($request);

        return new DocumentCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Devuelve los totales de la busqueda,
     *
     * Implementado en resources/js/views/tenant/documents/index.vue
     * @param Request $request
     *
     * @return array[]
     */
    public function recordsTotal(Request $request)
    {

        $FT_t = DocumentType::find('01');
        $BV_t = DocumentType::find('03');
        $NC_t = DocumentType::find('07');
        $ND_t = DocumentType::find('08');

        $BV = $this->getRecords($request)->where('document_type_id', $BV_t->id)->where('currency_type_id','PEN')->sum('total');
        $FT = $this->getRecords($request)->where('document_type_id', $FT_t->id)->where('currency_type_id','PEN')->sum('total');
        $NC = $this->getRecords($request)->where('document_type_id', $NC_t->id)->where('currency_type_id','PEN')->sum('total');
        $ND = $this->getRecords($request)->where('document_type_id', $ND_t->id)->where('currency_type_id','PEN')->sum('total');
        return [
            [
                'name' => $FT_t->description,
                'total' =>"S/. ". ReportHelper::setNumber($FT),
            ],
            [
                'name' => $BV_t->description,
                'total' => "S/. ".ReportHelper::setNumber($BV),

            ],
            [
                'name' => $NC_t->description,
                'total' => "S/. ".ReportHelper::setNumber($NC),
            ],
            [
                'name' => $ND_t->description,
                'total' => "S/. ".ReportHelper::setNumber($ND),
            ],
        ];
    }

    public function searchCustomers(Request $request)
    {

        //tru de boletas en env esta en true filtra a los con dni   , false a todos
        $identity_document_type_id = $this->getIdentityDocumentTypeId($request->document_type_id, $request->operation_type_id);
//        $operation_type_id_id = $this->getIdentityDocumentTypeId($request->operation_type_id);

        $customers = Person::where('number','like', "%{$request->input}%")
                            ->orWhere('name','like', "%{$request->input}%")
                            ->whereType('customers')->orderBy('name')
                            ->whereIn('identity_document_type_id',$identity_document_type_id)
                            ->whereIsEnabled()
                            ->whereFilterCustomerBySeller('customers')
                            ->get()->transform(function($row) {
                /** @var  Person $row */
                return $row->getCollectionData();
                /* Movido al modelo */
                return [
                                    'id' => $row->id,
                                    'description' => $row->number.' - '.$row->name,
                                    'name' => $row->name,
                                    'number' => $row->number,
                                    'identity_document_type_id' => $row->identity_document_type_id,
                                    'identity_document_type_code' => $row->identity_document_type->code,
                                    'addresses' => $row->addresses,
                                    'address' =>  $row->address
                                ];
                            });

        return compact('customers');
    }


    public function create()
    {
        if(auth()->user()->type == 'integrator')
            return redirect('/documents');

        $configuration = Configuration::first();
        $is_contingency = 0;
        return view('tenant.documents.form', compact('is_contingency', 'configuration'));
    }

    public function create_tensu()
    {
        if(auth()->user()->type == 'integrator')
            return redirect('/documents');

        $is_contingency = 0;
        return view('tenant.documents.form_tensu', compact('is_contingency'));
    }


    public function tables()
    {
        $customers = $this->table('customers');
        $user = new User();
        if(\Auth::user()){
            $user = \Auth::user();
        }
        $document_id =  $user->document_id;
        $series_id =  $user->series_id;
        $establishment_id =  $user->establishment_id;
        $userId =  $user->id;
        $userType = $user->type;
        $series = $user->getSeries();
        // $prepayment_documents = $this->table('prepayment_documents');
        $establishments = Establishment::where('id', $establishment_id)->get();// Establishment::all();
        $document_types_invoice = DocumentType::whereIn('id', ['01', '03'])->get();
        $document_types_note = DocumentType::whereIn('id', ['07', '08'])->get();
        $note_credit_types = NoteCreditType::whereActive()->orderByDescription()->get();
        $note_debit_types = NoteDebitType::whereActive()->orderByDescription()->get();
        $currency_types = CurrencyType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $company = Company::active();
        $document_type_03_filter = config('tenant.document_type_03_filter');
        // $sellers = User::where('establishment_id',$establishment_id)->whereIn('type', ['seller', 'admin'])->orWhere('id', $userId)->get();
        $sellers = User::getSellersToNvCpe($establishment_id,$userId)
            ->transform(function(User $row){
                return $row->getCollectionData();
            });
        $payment_method_types = $this->table('payment_method_types');
        $business_turns = BusinessTurn::where('active', true)->get();
        $enabled_discount_global = config('tenant.enabled_discount_global');
        $is_client = $this->getIsClient();
        $select_first_document_type_03 = config('tenant.select_first_document_type_03');
        $payment_conditions = PaymentCondition::all();

        $document_types_guide = DocumentType::whereIn('id', ['09', '31'])->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'active' => (bool) $row->active,
                'short' => $row->short,
                'description' => ucfirst(mb_strtolower(str_replace('REMITENTE ELECTRÓNICA','REMITENTE',$row->description))),
            ];
        });
        // $cat_payment_method_types = CatPaymentMethodType::whereActive()->get();
        // $detraction_types = DetractionType::whereActive()->get();

//        return compact('customers', 'establishments', 'series', 'document_types_invoice', 'document_types_note',
//                       'note_credit_types', 'note_debit_types', 'currency_types', 'operation_types',
//                       'discount_types', 'charge_types', 'company', 'document_type_03_filter',
//                       'document_types_guide');

        // return compact('customers', 'establishments', 'series', 'document_types_invoice', 'document_types_note',
        //                'note_credit_types', 'note_debit_types', 'currency_types', 'operation_types',
        //                'discount_types', 'charge_types', 'company', 'document_type_03_filter');

        $payment_destinations = $this->getPaymentDestinations();
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $user = $userType;
        $global_discount_types = ChargeDiscountType::whereIn('id', ['02', '03'])->whereActive()->get();

        return compact(
            'document_id',
            'series_id',
            'customers',
            'establishments',
            'series',
            'document_types_invoice',
            'document_types_note',
            'note_credit_types',
            'note_debit_types',
            'currency_types',
            'operation_types',
            'discount_types',
            'charge_types',
            'company',
            'document_type_03_filter',
            'document_types_guide',
            'user',
            'sellers',
            'payment_method_types',
            'enabled_discount_global',
            'business_turns',
            'is_client',
            'select_first_document_type_03',
            'payment_destinations',
            'payment_conditions',
            'global_discount_types',
            'affectation_igv_types'
        );

    }

    public function item_tables()
    {
        // $items = $this->table('items');
        $items = SearchItemController::getItemsToDocuments();
        $categories = [];
        $affectation_igv_types = AffectationIgvType::whereActive()->get();
        $system_isc_types = SystemIscType::whereActive()->get();
        $price_types = PriceType::whereActive()->get();
        $operation_types = OperationType::whereActive()->get();
        $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
        $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
        $attribute_types = AttributeType::whereActive()->orderByDescription()->get();
        $is_client = $this->getIsClient();

        $configuration= Configuration::first();

        /** Informacion adicional */
        $colors = collect([]);
        $CatItemSize=$colors;
        $CatItemStatus=$colors;
        $CatItemUnitBusiness = $colors;
        $CatItemMoldCavity = $colors;
        $CatItemPackageMeasurement =$colors;
        $CatItemUnitsPerPackage = $colors;
        $CatItemMoldProperty = $colors;
        $CatItemProductFamily= $colors;
        if($configuration->isShowExtraInfoToItem()){

            $colors = CatColorsItem::all();
            $CatItemSize= CatItemSize::all();
            $CatItemStatus= CatItemStatus::all();
            $CatItemUnitBusiness = CatItemUnitBusiness::all();
            $CatItemMoldCavity = CatItemMoldCavity::all();
            $CatItemPackageMeasurement = CatItemPackageMeasurement::all();
            $CatItemUnitsPerPackage = CatItemUnitsPerPackage::all();
            $CatItemMoldProperty = CatItemMoldProperty::all();
            $CatItemProductFamily= CatItemProductFamily::all();
        }


        /** Informacion adicional */

        return compact(
            'items',
            'categories',
            'affectation_igv_types',
            'system_isc_types',
            'price_types',
            'operation_types',
            'discount_types',
            'charge_types',
            'attribute_types',
            'is_client',
            'colors',
            'CatItemSize',
            'CatItemMoldCavity',
            'CatItemMoldProperty',
            'CatItemUnitBusiness',
            'CatItemStatus',
            'CatItemPackageMeasurement',
            'CatItemProductFamily',
            'CatItemUnitsPerPackage');
    }

    public function table($table)
    {
        if ($table === 'customers') {
            $customers = Person::with('addresses')
                               ->whereType('customers')
                               ->whereIsEnabled()
                               ->whereFilterCustomerBySeller('customers')
                               ->orderBy('name')
                               ->take(20)
                               ->get()->transform(function ($row) {
                    /** @var Person $row */
                    return $row->getCollectionData();
                    /** Se ha movido la salida, al modelo */
                    return [
                        'id'                          => $row->id,
                        'description'                 => $row->number.' - '.$row->name,
                        'name'                        => $row->name,
                        'number'                      => $row->number,
                        'identity_document_type_id'   => $row->identity_document_type_id,
                        'identity_document_type_code' => $row->identity_document_type->code,
                        'addresses'                   => $row->addresses,
                        'address'                     => $row->address,
                        'internal_code'               => $row->internal_code,
                    ];

                });
            return $customers;
        }

        if ($table === 'prepayment_documents') {
            $prepayment_documents = Document::whereHasPrepayment()->get()->transform(function($row) {

                $total = round($row->pending_amount_prepayment, 2);
                $amount = ($row->affectation_type_prepayment == '10') ? round($total/1.18, 2) : $total;

                return [
                    'id' => $row->id,
                    'description' => $row->series.'-'.$row->number,
                    'series' => $row->series,
                    'number' => $row->number,
                    'document_type_id' => ($row->document_type_id == '01') ? '02':'03',
                    // 'amount' => $row->total_value,
                    // 'total' => $row->total,
                    'amount' => $amount,
                    'total' => $total,

                ];
            });
            return $prepayment_documents;
        }

        if ($table === 'payment_method_types') {

            return PaymentMethodType::getPaymentMethodTypes();
            /*
            $payment_method_types = PaymentMethodType::whereNotIn('id', ['05', '08', '09'])->get();
            $end_payment_method_types = PaymentMethodType::whereIn('id', ['05', '08', '09'])->get(); //by requirement
            return $payment_method_types->merge($end_payment_method_types);
            */
        }

        if ($table === 'items') {

            return SearchItemController::getItemsToDocuments();

            $establishment_id = auth()->user()->establishment_id;
            $warehouse = ModuleWarehouse::where('establishment_id', $establishment_id)->first();
            // $items_u = Item::whereWarehouse()->whereIsActive()->whereNotIsSet()->orderBy('description')->take(20)->get();
            $items_u = Item::with('warehousePrices')
                ->whereIsActive()
                ->orderBy('description');
            $items_s = Item::with('warehousePrices')
                ->where('items.unit_type_id', 'ZZ')
                ->whereIsActive()
                ->orderBy('description');
            $items_u = $items_u
                ->take(20)
                ->get();
            $items_s = $items_s
                ->take(10)
                ->get();
            $items = $items_u->merge($items_s);

            return collect($items)->transform(function($row) use($warehouse){
                /** @var Item $row */
                return $row->getDataToItemModal($warehouse);
                $detail = $this->getFullDescription($row, $warehouse);
                return [
                    'id' => $row->id,
                    'full_description' => $detail['full_description'],
                    'model' => $row->model,
                    'brand' => $detail['brand'],
                    'warehouse_description' => $detail['warehouse_description'],
                    'category' => $detail['category'],
                    'stock' => $detail['stock'],
                    'internal_id' => $row->internal_id,
                    'description' => $row->description,
                    'currency_type_id' => $row->currency_type_id,
                    'currency_type_symbol' => $row->currency_type->symbol,
                    'sale_unit_price' => Item::getSaleUnitPriceByWarehouse($row, $warehouse->id),
                    'purchase_unit_price' => $row->purchase_unit_price,
                    'unit_type_id' => $row->unit_type_id,
                    'sale_affectation_igv_type_id' => $row->sale_affectation_igv_type_id,
                    'purchase_affectation_igv_type_id' => $row->purchase_affectation_igv_type_id,
                    'calculate_quantity' => (bool) $row->calculate_quantity,
                    'has_igv' => (bool) $row->has_igv,
                    'has_plastic_bag_taxes' => (bool) $row->has_plastic_bag_taxes,
                    'amount_plastic_bag_taxes' => $row->amount_plastic_bag_taxes,
                    'item_unit_types' => collect($row->item_unit_types)->transform(function($row) {
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
                    'warehouses' => collect($row->warehouses)->transform(function($row) use($warehouse){
                        return [
                            'warehouse_description' => $row->warehouse->description,
                            'stock' => $row->stock,
                            'warehouse_id' => $row->warehouse_id,
                            'checked' => ($row->warehouse_id == $warehouse->id) ? true : false,
                        ];
                    }),
                    'attributes' => $row->attributes ? $row->attributes : [],
                    'lots_group' => collect($row->lots_group)->transform(function($row){
                        return [
                            'id'  => $row->id,
                            'code' => $row->code,
                            'quantity' => $row->quantity,
                            'date_of_due' => $row->date_of_due,
                            'checked'  => false
                        ];
                    }),
                    'lots' => [],
                    'lots_enabled' => (bool) $row->lots_enabled,
                    'series_enabled' => (bool) $row->series_enabled,

                ];
            });
        }

        return [];
    }

    public function getFullDescription($row, $warehouse){

        $desc = ($row->internal_id)?$row->internal_id.' - '.$row->description : $row->description;
        $category = ($row->category) ? "{$row->category->name}" : "";
        $brand = ($row->brand) ? "{$row->brand->name}" : "";



        if($row->unit_type_id != 'ZZ')
        {
            if(isset($row['stock'])){
                $warehouse_stock = number_format($row['stock'],2);
            } else {
                $warehouse_stock = ($row->warehouses && $warehouse) ?
                    number_format($row->warehouses->where('warehouse_id', $warehouse->id)->first()->stock,2) :
                    0;
            }

            $stock = ($row->warehouses && $warehouse) ? "{$warehouse_stock}" : "";
        }
        else{
            $stock = '';
        }

        $desc = "{$desc} - {$brand}";

        return [
            'full_description' => $desc,
            'brand' => $brand,
            'category' => $category,
            'stock' => $stock,
            'warehouse_description' => $warehouse->description,
        ];
    }


    public function record($id)
    {
        $record = new DocumentResource(Document::findOrFail($id));

        return $record;
    }

    public function store(DocumentRequest $request)
    {

        $validate = $this->validateDocument($request);
        if(!$validate['success']) return $validate;

        $res = $this->storeWithData($request->all());
        $document_id = $res['data']['id'];
        $this->associateDispatchesToDocument($request, $document_id);
        $this->associateSaleNoteToDocument($request, $document_id);

        return $res;
    }


    /**
     * Validaciones previas al proceso de facturacion
     *
     * @param array $request
     * @return array
     */
    public function validateDocument($request)
    {

        // validar nombre de producto pdf en xml - items
        foreach ($request->items as $item) {

            if($item['name_product_xml']){
                // validar error 2027 sunat
                if(mb_strlen($item['name_product_xml']) > 500){
                    return [
                        'success' => false,
                        'message' => "El campo Nombre producto en PDF/XML no puede superar los 500 caracteres - Producto/Servicio: {$item['item']['description']}"
                    ];
                }
            }
        }

        return [
            'success' => true,
            'message' => ''
        ];

    }

    /**
     * Guarda los datos del hijo para el proceso de suscripcion. #952
     * Toma el valor de nota de venta y lo pasa para la boleta/factura
     *
     * @param $data
     */
    public static function setChildrenToData(&$data){
        $request = request();
        if(
            $request != null &&
            $request->has('sale_note_id') &&
            $request->sale_note_id
        ){
            $saleNote = SaleNote::find($request->sale_note_id);
            if($saleNote!=null && isset($data['customer'])){
                $customer = $data['customer'];
                $customerNote = (array) $saleNote->customer;
                if(isset($customerNote['children'])){
                    $customer['children'] = (array)$customerNote['children'];
                }
                $data['customer']=$customer;
                $data['grade']=$saleNote->getGrade();
                $data['section']=$saleNote->getSection();
            }
        }
    }

    /**
     * @param array $data
     *
     * @return array
     * @throws \Throwable
     */
    public function storeWithData($data)
    {
        self::setChildrenToData($data);
        $fact = DB::connection('tenant')->transaction(function () use ($data) {
            $facturalo = new Facturalo();
            $facturalo->save($data);
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->updateHash();
            $facturalo->updateQr();
            $facturalo->createPdf();
            $facturalo->senderXmlSignedBill();

            return $facturalo;
        });

        $document = $fact->getDocument();
        $response = $fact->getResponse();

        return [
          'success' => true,
          'data' => [
              'id' => $document->id,
              'response' =>$response
          ]
        ];
    }

    private function associateSaleNoteToDocument(Request $request, int $documentId)
    {
        if ($request->sale_note_id) {
            SaleNote::where('id', $request->sale_note_id)
                ->update(['document_id' => $documentId]);
        }

        //notas de venta relacionadas cuando se genera cpe desde multiples nv
        $notes = $request->sale_notes_relateds;

        if ($notes) {

            foreach ($notes as $note) {

                $sale_note_id = $note['id'] ?? null;

                if ($sale_note_id) {

                    $sale_note = SaleNote::find($sale_note_id);

                    if(!empty($sale_note)) {
                        $sale_note->document_id = $documentId;
                        $sale_note->push();
                    }

                }

                // $noteArray = explode('-', $note);
                // if (count($noteArray) === 2) {
                //     $sale_note = SaleNote::where([
                //                                      'series'=> $noteArray[0],
                //                                      'number'=> $noteArray[1],
                //                                  ])->first();
                //     if(!empty($sale_note)) {
                //         $sale_note->document_id = $documentId;
                //         $sale_note->push();
                //     }
                // }
            }
        }
    }

    private function associateDispatchesToDocument(Request $request, int $documentId)
    {
        $dispatches_relateds = $request->dispatches_relateds;
        if ($dispatches_relateds) {
            foreach ($dispatches_relateds as $dispatch) {
                $dispatchToArray = explode('-', $dispatch);
                if (count($dispatchToArray) === 2) {
                    Dispatch::where('series', $dispatchToArray[0])
                        ->where('number', $dispatchToArray[1])
                        ->update([
                            'reference_document_id' => $documentId,
                        ]);

                    $document = Dispatch::where('series', $dispatchToArray[0])
                        ->where('number', $dispatchToArray[1])
                        ->first();

                    if ($document) {
                        $facturalo = new Facturalo();
                        $facturalo->createPdf($document, 'dispatch', 'a4');
                    }
                }
            }
        }
    }

    public function edit($documentId)
    {
        if(auth()->user()->type == 'integrator') {
            return redirect('/documents');
        }
        $configuration = Configuration::first();
        $is_contingency = 0;
        $isUpdate = true;
        return view('tenant.documents.form', compact('is_contingency', 'configuration', 'documentId', 'isUpdate'));
    }

    /**
     * @param \App\Http\Requests\Tenant\DocumentUpdateRequest $request
     * @param                                                 $id
     *
     * @return array
     * @throws \Throwable
     */
    public function update(DocumentUpdateRequest $request, $id)
    {

        $validate = $this->validateDocument($request);
        if(!$validate['success']) return $validate;

        $fact = DB::connection('tenant')->transaction(function () use ($request, $id) {
            $facturalo = new Facturalo();
            $facturalo->update($request->all(), $id);

            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->updateHash();
            $facturalo->updateQr();
            $facturalo->createPdf();

            return $facturalo;
        });

        $document = $fact->getDocument();
        $response = $fact->getResponse();

        return [
            'success' => true,
            'data'    => [
                'id'       => $document->id,
                'response' => $response,
            ],
        ];
    }

    public function show($documentId)
    {
        $document = Document::findOrFail($documentId);
        return response()->json([
            'data' => $document,
            'success' => true,
        ], 200);
    }

    public function reStore($document_id)
    {
        $fact = DB::connection('tenant')->transaction(function () use ($document_id) {
            $document = Document::find($document_id);

            $type = 'invoice';
            if($document->document_type_id === '07') {
                $type = 'credit';
            }
            if($document->document_type_id === '08') {
                $type = 'debit';
            }

            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->setType($type);
            $facturalo->createXmlUnsigned();
            $facturalo->signXmlUnsigned();
            $facturalo->updateHash();
            $facturalo->updateQr();
            $facturalo->updateSoap('02', $type);
            $facturalo->updateState('01');
            $facturalo->createPdf($document, $type, 'ticket');
//            $facturalo->senderXmlSignedBill();
        });

//        $document = $fact->getDocument();
//        $response = $fact->getResponse();

        return [
            'success' => true,
            'message' => 'El documento se volvio a generar.',
        ];
    }

    public function email(DocumentEmailRequest $request)
    {
        $company = Company::active();
        $document = Document::find($request->input('id'));
        $customer_email = $request->input('customer_email');
        $email = $customer_email;
        $mailable =  new DocumentEmail($company, $document);
        $id = (int) $request->input('id');
        $sendIt = EmailController::SendMail($email, $mailable, $id, 1);
        // Centralizar el envio de correos a Email Controller
        /*
        Configuration::setConfigSmtpMail();
        $array_customer = explode(',', $customer_email);
        if (count($array_customer) > 1) {
            foreach ($array_customer as $customer) {
                Mail::to($customer)->send(new DocumentEmail($company, $document));
            }
        } else {
            Mail::to($customer_email)->send(new DocumentEmail($company, $document));
        }
        */
        return [
            'success' => true
        ];
    }

    public function send($document_id) {
        $document = Document::find($document_id);

        $fact = DB::connection('tenant')->transaction(function () use ($document) {
            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->loadXmlSigned();
            $facturalo->onlySenderXmlSignedBill();
            return $facturalo;
        });

        $response = $fact->getResponse();

        return [
            'success' => true,
            'message' => $response['description'],
        ];
    }

    public function consultCdr($document_id)
    {
        $document = Document::find($document_id);

        $fact = DB::connection('tenant')->transaction(function () use ($document) {
            $facturalo = new Facturalo();
            $facturalo->setDocument($document);
            $facturalo->consultCdr();
        });

        $response = $fact->getResponse();

        return [
            'success' => true,
            'message' => $response['description'],
        ];
    }

    public function sendServer($document_id, $query = false) {
        $document = Document::find($document_id);
        // $bearer = config('tenant.token_server');
        // $api_url = config('tenant.url_server');
        $bearer = $this->getTokenServer();
        $api_url = $this->getUrlServer();
        $client = new Client(['base_uri' => $api_url, 'verify' => false]);

       // $zipFly = new ZipFly();
        if(!$document->data_json) throw new Exception("Campo data_json nulo o inválido - Comprobante: {$document->fullnumber}");

        $data_json = (array) $document->data_json;
        $data_json['numero_documento'] = $document->number;
        $data_json['external_id'] = $document->external_id;
        $data_json['hash'] = $document->hash;
        $data_json['qr'] = $document->qr;
        $data_json['query'] = $query;
        $data_json['file_xml_signed'] = base64_encode($this->getStorage($document->filename, 'signed'));
        $data_json['file_pdf'] = base64_encode($this->getStorage($document->filename, 'pdf'));
        // dd($data_json);
        $res = $client->post('/api/documents_server', [
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer '.$bearer,
                'Accept' => 'application/json',
            ],
            'form_params' => $data_json
        ]);

        $response = json_decode($res->getBody()->getContents(), true);

        if ($response['success']) {
            $document->send_server = true;
            $document->save();
        }

        return $response;
    }

    public function checkServer($document_id) {
        $document = Document::find($document_id);
        $bearer = $this->getTokenServer();
        $api_url = $this->getUrlServer();

        $client = new Client(['base_uri' => $api_url, 'verify' => false]);

        $res = $client->get('/api/document_check_server/'.$document->external_id, [
            'headers' => [
                'Authorization' => 'Bearer '.$bearer,
                'Accept' => 'application/json',
            ],
        ]);

        $response = json_decode($res->getBody()->getContents(), true);

        if ($response['success']) {
            $state_type_id = $response['state_type_id'];
            $document->state_type_id = $state_type_id;
            $document->save();

            if ($state_type_id === '05') {
                $this->uploadStorage($document->filename, base64_decode($response['file_cdr']), 'cdr');
            }
        }

        return $response;
    }

    public function searchCustomerById($id)
    {

        $customers = Person::with('addresses')->whereType('customers')
                    ->where('id',$id)
                    ->whereFilterCustomerBySeller('customers')
                    ->get()->transform(function($row) {
                        /** @var  Person $row */
                        return $row->getCollectionData();
                        /* Movido al modelo */
                        return [
                            'id' => $row->id,
                            'description' => $row->number.' - '.$row->name,
                            'name' => $row->name,
                            'number' => $row->number,
                            'identity_document_type_id' => $row->identity_document_type_id,
                            'identity_document_type_code' => $row->identity_document_type->code,
                            'addresses' => $row->addresses,
                            'address' =>  $row->address
                        ];
                    });

        return compact('customers');
    }

    public function getIdentityDocumentTypeId($document_type_id, $operation_type_id){

        // if($operation_type_id === '0101' || $operation_type_id === '1001') {

        if(in_array($operation_type_id, ['0101', '1001', '1004'])) {

            if($document_type_id == '01'){
                $identity_document_type_id = [6];
            }else{
                if(config('tenant.document_type_03_filter')){
                    $identity_document_type_id = [1];
                }else{
                    $identity_document_type_id = [1,4,6,7,0];
                }
            }
        } else {
            $identity_document_type_id = [1,4,6,7,0];
        }

        return $identity_document_type_id;
    }

    public function changeToRegisteredStatus($document_id)
    {
        $document = Document::find($document_id);
        if($document->state_type_id === '01') {
            $document->state_type_id = '05';
            $document->save();

            return [
                'success' => true,
                'message' => 'El estado del documento fue actualizado.',
            ];
        }
    }

    public function import(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                $import = new DocumentsImport();
                $import->import($request->file('file'), null, Excel::XLSX);
                $data = $import->getData();
                return [
                    'success' => true,
                    'message' =>  __('app.actions.upload.success'),
                    'data' => $data
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' =>  $e->getMessage()
                ];
            }
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }

    public function importTwoFormat(Request $request)
    {
        if ($request->hasFile('file')) {
            try {
                $import = new DocumentsImportTwoFormat();
                $import->import($request->file('file'), null, Excel::XLSX);
                $data = $import->getData();
                return [
                    'success' => true,
                    'message' =>  __('app.actions.upload.success'),
                    'data' => $data
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' =>  $e->getMessage()
                ];
            }
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }

    public function messageLockedEmission(){

        $exceed_limit = DocumentHelper::exceedLimitDocuments();

        if($exceed_limit['success'])
        {
            return [
                'success' => false,
                'message' => $exceed_limit['message'],
            ];
        }

        // $configuration = Configuration::first();
        // $quantity_documents = Document::count();
        // $quantity_documents = $configuration->quantity_documents;

        // if($configuration->limit_documents !== 0 && ($quantity_documents > $configuration->limit_documents))
        //     return [
        //         'success' => false,
        //         'message' => 'Alcanzó el límite permitido para la emisión de comprobantes',
        //     ];


        return [
            'success' => true,
            'message' => '',
        ];
    }

    public function getRecords($request){


        $d_end = $request->d_end;
        $d_start = $request->d_start;
        $date_of_issue = $request->date_of_issue;
        $document_type_id = $request->document_type_id;
        $state_type_id = $request->state_type_id;
        $number = $request->number;
        $series = $request->series;
        $pending_payment = ($request->pending_payment == "true") ? true:false;
        $customer_id = $request->customer_id;
        $item_id = $request->item_id;
        $category_id = $request->category_id;
        $purchase_order = $request->purchase_order;
        $guides = $request->guides;
        $plate_numbers = $request->plate_numbers;

        $records = Document::query();
		if ($d_start && $d_end) {
			 $records->whereBetween('date_of_issue', [$d_start, $d_end]);
		}
        if ($date_of_issue) {
            $records = Document::where('date_of_issue', 'like', '%' . $date_of_issue . '%');
        }
        /** @var Builder $records */
        if ($document_type_id) {
            $records->where('document_type_id', 'like', '%' . $document_type_id . '%');
        }
        if ($series) {
            $records->where('series', 'like', '%' . $series . '%');
        }
        if ($number) {
            $records->where('number', $number);
        }
        if ($state_type_id) {
            $records->where('state_type_id', 'like', '%' . $state_type_id . '%');
        }
        if ($purchase_order) {
            $records->where('purchase_order', $purchase_order);
        }
        $records->whereTypeUser()->latest();

        if ($pending_payment) {
            $records->where('total_canceled', false);
        }

        if ($customer_id) {
            $records->where('customer_id', $customer_id);
        }

        if ($item_id) {
            $records->whereHas('items', function ($query) use ($item_id) {
                $query->where('item_id', $item_id);
            });
        }

        if ($category_id) {
            $records->whereHas('items', function ($query) use ($category_id) {
                $query->whereHas('relation_item', function ($q) use ($category_id) {
                    $q->where('category_id', $category_id);
                });
            });
        }
        if (!empty($guides)) {
            $records->where('guides', 'like', DB::raw("%\"number\":\"%") . $guides . DB::raw("%\"%"));
        }
        if ($plate_numbers) {
            $records->where('plate_number', 'like', '%' . $plate_numbers . '%');
        }
        return $records;
    }

    public function data_table()
    {

        $customers = $this->table('customers');
        $items = $this->getItems();
        $categories = Category::orderBy('name')->get();
        $state_types = StateType::get();
        $document_types = DocumentType::whereIn('id', ['01', '03','07', '08'])->get();
        $series = Series::whereIn('document_type_id', ['01', '03','07', '08'])->get();
        $establishments = Establishment::where('id', auth()->user()->establishment_id)->get();// Establishment::all();

        return compact( 'customers', 'document_types','series','establishments', 'state_types', 'items', 'categories');

    }


    public function getItems(){

        $items = Item::orderBy('description')->take(20)->get()->transform(function($row) {
            return [
                'id' => $row->id,
                'description' => ($row->internal_id) ? "{$row->internal_id} - {$row->description}" :$row->description,
            ];
        });

        return $items;

    }


    public function getDataTableItem(Request $request) {

        $items = Item::where('description','like', "%{$request->input}%")
                        ->orWhere('internal_id','like', "%{$request->input}%")
                        ->orderBy('description')
                        ->get()->transform(function($row) {
                            return [
                                'id' => $row->id,
                                'description' => ($row->internal_id) ? "{$row->internal_id} - {$row->description}" :$row->description,
                            ];
                        });

        return $items;

    }


    private function updateMaxCountPayments($value)
    {
        if($value > $this->max_count_payment)
        {
            $this->max_count_payment = $value;
        }
       // $this->max_count_payment = 20 ;//( $value > $this->max_count_payment) ? $value : $this->$max_count_payment;
    }

    private function transformReportPayment($resource)
    {

        $records = $resource->transform(function($row) {

            $total_paid = collect($row->payments)->sum('payment');
            $total = $row->total;
            $total_difference = round($total - $total_paid, 2);

            $this->updateMaxCountPayments($row->payments->count());

            return (object)[

                'id' => $row->id,
                'ruc' => $row->customer->number,
                // 'date' =>  $row->date_of_issue->format('Y-m-d'),
                // 'date' =>  $row->date_of_issue,
                'date' =>  $row->date_of_issue->format('d/m/Y'),
                'invoice' => $row->number_full,
                'comercial_name' => $row->customer->trade_name,
                'business_name' => $row->customer->name,
                'zone' => $row->customer->department->description,
                'total' => number_format($row->total, 2, ".",""),

                'payments' => $row->payments,

                /*'payment1' =>  ( isset($row->payments[0]) ) ?  number_format($row->payments[0]->payment, 2) : '',
                'payment2' =>  ( isset($row->payments[1]) ) ?  number_format($row->payments[1]->payment, 2) : '',
                'payment3' =>   ( isset($row->payments[2]) ) ?  number_format($row->payments[2]->payment, 2) : '',
                'payment4' =>   ( isset($row->payments[3]) ) ?  number_format($row->payments[3]->payment, 2) : '', */

                'balance' => $total_difference,
                'person_type' => isset($row->person->person_type->description) ? $row->person->person_type->description:'',
                'department' => $row->customer->department->description,
                'district' => $row->customer->district->description,

                /*'reference1' => ( isset($row->payments[0]) ) ?  $row->payments[0]->reference : '',
                'reference2' =>  ( isset($row->payments[1]) ) ?  $row->payments[1]->reference : '',
                'reference3' =>  ( isset($row->payments[2]) ) ?  $row->payments[2]->reference : '',
                'reference4' =>  ( isset($row->payments[3]) ) ?  $row->payments[3]->reference : '', */
            ];
        });

        return $records;
    }

    public function report_payments(Request $request)
    {
        // $month_format = Carbon::parse($month)->format('m');

        if($request->anulled == 'true') {
           $records = Document::whereBetween('date_of_issue', [$request->date_start, $request->date_end])->get();
        } else {
            $records = Document::whereBetween('date_of_issue', [$request->date_start, $request->date_end])->where('state_type_id', '!=', '11')->get();
        }

        $source =  $this->transformReportPayment( $records );

        return (new PaymentExport)
                ->records($source)
                ->payment_count($this->max_count_payment)
                ->download('Reporte_Pagos_'.Carbon::now().'.xlsx');

    }

    public function destroyDocument($document_id)
    {
        try {

            DB::connection('tenant')->transaction(function () use ($document_id) {

                $record = Document::findOrFail($document_id);
                $this->deleteAllPayments($record->payments);
                $record->delete();

            });

            return [
                'success' => true,
                'message' => 'Documento eliminado con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => 'El Documento esta siendo usada por otros registros, no puede eliminar'] : ['success' => false,'message' => 'Error inesperado, no se pudo eliminar el Documento'];

        }


    }

    public function storeCategories(CategoryRequest $request)
    {
        $id = $request->input('id');
        $category = Category::firstOrNew(['id' => $id]);
        $category->fill($request->all());
        $category->save();


        return [
            'success' => true,
            'message' => ($id)?'Categoría editada con éxito':'Categoría registrada con éxito',
            'data' => $category

        ];
    }

    public function storeBrands(BrandRequest $request){
         $id = $request->input('id');
        $brand = Brand::firstOrNew(['id' => $id]);
        $brand->fill($request->all());
        $brand->save();


        return [
            'success' => true,
            'message' => ($id)?'Marca editada con éxito':'Marca registrada con éxito',
            'data' => $brand
        ];
    }

    public function searchExternalId(Request $request)
    {
        return response()->json(Document::where('external_id', $request->external_id)->first());
    }

}
