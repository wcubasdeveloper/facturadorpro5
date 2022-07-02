<?php

    namespace Modules\Production\Http\Controllers;

    use App\CoreFacturalo\Requests\Inputs\Common\PersonInput;
    use App\Models\Tenant\Catalogs\AffectationIgvType;
    use App\Models\Tenant\Catalogs\AttributeType;
    use App\Models\Tenant\Catalogs\ChargeDiscountType;
    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Catalogs\OperationType;
    use App\Models\Tenant\Catalogs\PriceType;
    use App\Models\Tenant\Catalogs\SystemIscType;
    use App\Models\Tenant\Catalogs\UnitType;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\ItemSupply;
    use App\Models\Tenant\ItemUnitType;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\Warehouse;
    use App\Traits\OfflineTrait;
    use Barryvdh\DomPDF\Facade as PDF;
    use Carbon\Carbon;
    use Illuminate\Database\Query\Builder;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Str;
    use Modules\Expense\Exports\ExpenseExport;
    use Modules\Expense\Models\Expense;
    use Modules\Expense\Models\ExpenseMethodType;
    use Modules\Expense\Models\ExpenseReason;
    use Modules\Expense\Models\ExpenseType;
    use Modules\Finance\Traits\FinanceTrait;
    //use Modules\Inventory\Traits\InventoryTrait;
    use Modules\Inventory\Traits\InventoryTrait;
    use Modules\Production\Exports\MillExport;
    use Modules\Production\Http\Requests\MillRequest;
    use Modules\Production\Http\Resources\MillCollection;
    use Modules\Production\Models\Mill;
    use Modules\Production\Models\MillItem;
    use Modules\Inventory\Models\InventoryTransaction;
    use Modules\Inventory\Models\Inventory;
    use Modules\Production\Models\Production;


    class MillController extends Controller
    {
        use InventoryTrait;
        use FinanceTrait;
        use OfflineTrait;

        public static function merge_inputs($inputs)
        {

            $company = Company::active();

            $values = [
                'user_id' => auth()->id(),
                'state_type_id' => $inputs['id'] ? $inputs['state_type_id'] : '05',
                'soap_type_id' => $company->soap_type_id,
                'external_id' => $inputs['id'] ? $inputs['external_id'] : Str::uuid()->toString(),
                'supplier' => PersonInput::set($inputs['supplier_id']),
            ];

            $inputs->merge($values);

            return $inputs->all();
        }

        public function columns()
        {
            return [

                'name' => 'Numero de registro',
                'date_start' => 'Fecha de inicio',
                // 'time_start'=> '',
                'date_end' => 'Fecha de fin',
                // 'time_end'=> '',
            ];
        }

        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function index()
        {
            return view('production::mill.index');
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return Response
         */

        public function create($id = null)
        {
            return view('production::mill.form', compact('id'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param MillRequest $request
         *
         * @return Response
         */
        public function store(MillRequest $request)
        {


            $model = Mill::firstOrNew(['id' => null]);
            $model->fill($request->all());
            if(empty($model->user_id)) {
                $model->user_id = \Auth::user()->id;
            }
            $model->soap_type_id = $this->getCompanySoapTypeId();
            $model->save();

            $userWarehouse = \Auth::user()->establishment;
            $warehouse_id = $request['warehouse_id'] ?? $userWarehouse->id; // si no hay warehuse. pega el establecimiento del usuario


            foreach ($request->items as $item) {

                $quantity = $item['quantity'];
                $unit_type_id = (int)$item['unit_id'];
                $unit_type = null;
                if($unit_type_id != 0){
                    $unit_type = ItemUnitType::find($unit_type_id);
                    $quantity *= $unit_type->quantity_unit;
                }

                $mill_item = new MillItem();
                $mill_item->fill($item);
                $mill_item->mill_id = $model->id;
                $mill_item->save();


                $inventory_transaction = InventoryTransaction::findOrFail('100'); //debe ser ingreso por molino

                $inventory = new Inventory();
                $inventory->type = null;
                $inventory->description = $inventory_transaction->name;
                $inventory->item_id = $mill_item->item_id;
                $inventory->warehouse_id = $warehouse_id;
                $inventory->quantity = $quantity;
                $inventory->inventory_transaction_id = $inventory_transaction->id;
                $inventory->save();


            }

            return [
                'success' => true,
                'message' => 'Datos guardada de forma correcta.'
            ];

        }

        /**
         * Show the specified resource.
         *
         * @param int $id
         *
         * @return Response
         */
        public function show($id)
        {
            return view('production::show');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param int $id
         *
         * @return Response
         */
        public function edit($id)
        {
            return view('production::edit');
        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param int     $id
         *
         * @return Response
         */
        public function update(Request $request, $id)
        {
            //
        }

        /*
        public function tables() {
            $warehouses = Warehouse::all();
            return compact('warehouses');

        }
        */

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         *
         * @return Response
         */
        public function destroy($id)
        {
            //
        }

        public function tables()
        {
            $suppliers = $this->table('suppliers');
            $establishment = Establishment::where('id', auth()->user()->establishment_id)->first();
            $currency_types = CurrencyType::whereActive()->get();
            $expense_types = ExpenseType::get();
            $expense_method_types = ExpenseMethodType::all();
            $expense_reasons = ExpenseReason::all();
            $unit_types = UnitType::all();
            $payment_destinations = $this->getBankAccounts();

            return compact(
                'suppliers',
 'establishment',
 'currency_types',
 'expense_types',
 'expense_method_types',
 'expense_reasons',
 'unit_types',
 'payment_destinations');
        }

        public static function optionsItemSupplies(){
            // $ids = ItemSupply::select('individual_item_id')->distinct()->pluck('individual_item_id');
            return Item::ProductSupply()
                ->get()
                ->transform(function (Item $row) {
                    return  $row->getCollectionData();
                });
            return collect($records)->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'description' => $row->description
                ];
            });
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
                            'identity_document_type_id' => $row->identity_document_type_id,
                            'identity_document_type_code' => $row->identity_document_type->code
                        ];
                    });
                    return $suppliers;
                case 'items':

                    // $warehouse = Warehouse::where('establishment_id', auth()->user()->establishment_id)->first();

                    $items = self::optionsItemSupplies();
                    /*
                    $items = Item::orderBy('description')->whereIsActive()
                        // ->ForProduction()
                        // ->with(['warehouses' => function($query) use($warehouse){
                        //     return $query->where('warehouse_id', $warehouse->id);
                        // }])
                        ->take(20)->get();

                    */
                    //$this->ReturnItem($items);

                    return $items;

                    break;
                default:
                    return [];

                    break;
            }
        }
        public function ReturnItem( &$item)
    {
        $configuration = Configuration::first();
        $establishment_id = auth()->user()->establishment_id;
        $warehouse = \Modules\Inventory\Models\Warehouse::where('establishment_id', $establishment_id)->first();

        $item->transform(function ($row) use ($configuration, $warehouse) {
            /** @var \App\Models\Tenant\Item $row */
            return $row->getDataToItemModal($warehouse, false, true);
        });
    }

        public function records(Request $request)
        {

            $records = $this->getRecords($request);
            return new MillCollection($records->paginate(config('tenant.items_per_page')));

        }
        /**
         * @param Request $request
         *
         * @return \Illuminate\Database\Eloquent\Builder|Builder|Production
         */
        public function getDatesOfPeriod($request)
        {

            if ($request->has('form')) {
                $request = json_decode($request->form, true);
            }
            $period = $request['period'];
            $date_start = $request['date_start'];
            $date_end = $request['date_end'];
            $month_start = $request['month_start'];
            $month_end = $request['month_end'];

            $d_start = Carbon::now()->startOfMonth()->format('Y-m-d');
            $d_end = Carbon::now()->endOfMonth()->format('Y-m-d');
            /** @todo: Eliminar periodo, fechas y cambiar por
             * $date_start = $request['date_start'];
             * $date_end = $request['date_end'];
             * \App\CoreFacturalo\Helpers\Functions\FunctionsHelper\FunctionsHelper::setDateInPeriod($request, $date_start, $date_end);
             */
            switch ($period) {
                case 'month':
                    $d_start = Carbon::parse($month_start . '-01')->format('Y-m-d');
                    $d_end = Carbon::parse($month_start . '-01')->endOfMonth()->format('Y-m-d');
                    break;
                case 'between_months':
                    $d_start = Carbon::parse($month_start . '-01')->format('Y-m-d');
                    $d_end = Carbon::parse($month_end . '-01')->endOfMonth()->format('Y-m-d');
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


            return [
                'd_start' => $d_start,
                'd_end' => $d_end
            ];
        }   public function getRecords(Request $request)
        {
            $data_of_period = $this->getDatesOfPeriod($request);

            $data = Mill::query();
            if (!empty($data_of_period['d_start'])) {
                $data->where('date_start', '>=', $data_of_period['d_start']);
            }
            if (!empty($data_of_period['d_end'])) {
                $data->where('date_end', '<=', $data_of_period['d_end']);
            }
            return $data;
        }

        public function record($id)
        {
            $record = new ExpenseResource(Expense::findOrFail($id));

            return $record;
        }

        public function voided($record)
        {
            try {
                $expense = Expense::findOrFail($record);
                $expense->state_type_id = 11;
                $expense->save();
                return [
                    'success' => true,
                    'data' => [
                        'id' => $expense->id,
                    ],
                    'message' => 'Gasto anulado exitosamente',
                ];
            } catch (Exception $e) {
                return [
                    'success' => false,
                    'data' => [
                        'id' => $record,
                    ],
                    'message' => 'Falló al anular',
                ];
            }
        }


        public function item_tables() {

            $items = $this->table('items');
            // $items = SearchItemController::getItemToContract();
            $categories = [];
            $affectation_igv_types = AffectationIgvType::whereActive()->get();
            $system_isc_types = SystemIscType::whereActive()->get();
            $price_types = PriceType::whereActive()->get();
            $discount_types = ChargeDiscountType::whereType('discount')->whereLevel('item')->get();
            $charge_types = ChargeDiscountType::whereType('charge')->whereLevel('item')->get();
            $attribute_types = AttributeType::whereActive()->orderByDescription()->get();
            $unit_types = UnitType::all();

            $operation_types = OperationType::whereActive()->get();
            $is_client = $this->getIsClient();

            return compact(
                'items',
                'categories',
                'affectation_igv_types',
                'system_isc_types',
                'price_types',
                'discount_types',
                'charge_types',
                'attribute_types',
                'unit_types',
                'operation_types',
                'is_client'
            );
        }





        /**
         * @param Request $request
         *
         * @return Response|BinaryFileResponse
         */
        public function excel(Request $request)
        {
            // $records = $this->getData($request);
            $records = $this->getRecords($request)->get()->transform(function (Mill $row) {
                return $row->getCollectionData();
            });

            $MillExport = new MillExport();
            $MillExport->setCollection($records);
            $filename = 'Reporte de insumos - ' . date('YmdHis');
             // return $MillExport->view();
            return $MillExport->download($filename . '.xlsx');


        }


        public function pdf(Request $request) {
            // $records = $this->getData($request);

            $records = $this->getRecords($request)->get()->transform(function (Mill $row) {
                return $row->getCollectionData();
            });

            /** @var \Barryvdh\DomPDF\PDF $pdf */
            $pdf = PDF::loadView('production::mill.partial.export',
                compact(
                    'records'
                ))
                ->setPaper('a4', 'landscape');


            $filename = 'Reporte de insumos - ' . date('YmdHis');
            return $pdf->stream($filename.'.pdf');
        }
    }
