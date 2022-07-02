<?php

    namespace Modules\Production\Http\Controllers;


    use App\Models\Tenant\Item;
    use Barryvdh\DomPDF\Facade as PDF;
    use Carbon\Carbon;
    use Illuminate\Database\Query\Builder;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Facades\DB;
    use Modules\Finance\Traits\FinanceTrait;
    use Modules\Inventory\Models\Inventory;
    use Modules\Inventory\Models\InventoryTransaction;
    use Modules\Inventory\Traits\InventoryTrait;
    use Modules\Production\Exports\BuildProductsExport;
    use Modules\Production\Http\Requests\ProductionRequest;
    use Modules\Production\Http\Resources\ProductionCollection;
    use Modules\Production\Models\Machine;
    use Modules\Production\Models\Production;


    class ProductionController extends Controller
    {
        use InventoryTrait;
        use FinanceTrait;

        /**
         * Display a listing of the resource.
         *
         * @return Response
         */
        public function index()
        {
            return view('production::production.index');
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return Response
         */
        public function create()
        {
            return view('production::production.form');
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param ProductionRequest $request
         *
         * @return Response
         */
        public function store(ProductionRequest $request)
        {


            $result = DB::connection('tenant')->transaction(function () use ($request) {

                $item_id = $request->input('item_id');
                $warehouse_id = $request->input('warehouse_id');
                //$inventory_transaction_id = '19';  //Ingreso de producción
                $quantity = $request->input('quantity');
                $informative = ($request->informative) ?: false;


                $inventory_transaction = InventoryTransaction::findOrFail(19); //debe ser Ingreso de producción
                $inventory = new Inventory();

                if ($informative !== true) {
                    $inventory->type = null;
                    $inventory->description = $inventory_transaction->name;
                    $inventory->item_id = $item_id;
                    $inventory->warehouse_id = $warehouse_id;
                    $inventory->quantity = $quantity;
                    $inventory->inventory_transaction_id = $inventory_transaction->id;
                    $inventory->save();
                }

                $production = Production::firstOrNew(['id' => null]);
                $production->fill($request->all());
                $production->inventory_id_reference = $inventory->id;
                $production->user_id = auth()->user()->id;
                $production->soap_type_id = $this->getCompanySoapTypeId();
                $production->save();


                if ($informative !== true) {
                    $items_supplies = $request->supplies;
                    foreach ($items_supplies as $item) {
                        $supplyWarehouseId = (int)($item['warehouse_id'] ?? $warehouse_id);
                        $supplyWarehouseId = $supplyWarehouseId !== 0 ? $supplyWarehouseId : $warehouse_id;
                        $qty = $item['quantity'] ?? 0;
                        $inventory_transaction_item = InventoryTransaction::findOrFail('101'); //Salida insumos por molino
                        $inventory_it = new Inventory();
                        $inventory_it->type = null;
                        $inventory_it->description = $inventory_transaction_item->name;
                        $inventory_it->item_id = $item['individual_item_id'];
                        $inventory_it->warehouse_id = $supplyWarehouseId;
                        $inventory_it->quantity = (float)($qty * $quantity);
                        $inventory_it->inventory_transaction_id = $inventory_transaction_item->id;
                        $inventory_it->save();
                    }
                }

                return [
                    'success' => true,
                    'message' => 'Ingreso registrado correctamente'
                ];
            });

            return $result;

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
            $machines = Machine::query()->get()->transform(function (Machine $row) {
                return $row->getCollectionData();
            });
            return [
                'items' => self::optionsItemProduction(),
                'warehouses' => $this->optionsWarehouse(),
                'machines' => $machines
            ];
        }

        public static function optionsItemProduction()
        {
            return Item::ProductEnded()
                ->get()
                ->transform(function (Item $row) {
                    $data = $row->getCollectionData();


                    return $data;

                });
        }

        public function searchItems(Request $request)
        {
            $search = $request->input('search');

            return [
                'items' => self::optionsItemFullProduction($search, 20),
            ];
        }

        public static function optionsItemFullProduction($search = null, $take = null)
        {
            $query = Item::query()
                ->ProductEnded()
                ->with('item_lots', 'item_lots.item_loteable', 'lots_group');
            if ($search) {
                $query->where('description', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%")
                    ->orWhere('internal_id', 'like', "%{$search}%");
            }
            if ($take) {
                $query->take($take);
            }
            return $query->get()->transform(function (Item $row) {
                return $row->getCollectionData();
            });
        }

        public function records(Request $request)
        {
            $records = $this->getRecords($request);
            return new ProductionCollection($records->paginate(config('tenant.items_per_page')));

        }

        public function getRecords(Request $request)
        {
            $data_of_period = $this->getDatesOfPeriod($request);

            $data = Production::query();
            if (!empty($data_of_period['d_start'])) {
                $data->where('date_start', '>=', $data_of_period['d_start']);
            }
            if (!empty($data_of_period['d_end'])) {
                $data->where('date_end', '<=', $data_of_period['d_end']);
            }
            return $data;
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
        }

        /**
         * @param Request $request
         *
         * @return Response|BinaryFileResponse
         */
        public function excel(Request $request)
        {
            // $records = $this->getData($request);
            $records = $this->getRecords($request)->where('informative', 0)->get()->transform(function (Production $row) {
                return $row->getCollectionData();
            });

            $buildProductsExport = new BuildProductsExport();
            $buildProductsExport->setCollection($records);
            $filename = 'Reporte de produccion - ' . date('YmdHis');
            // return $buildProductsExport->view();
            return $buildProductsExport->download($filename . '.xlsx');


        }

        /**
         * @param Request $request
         *
         * @return Response|BinaryFileResponse
         */
        public function excel2(Request $request)
        {
            // $records = $this->getData($request);
            $records = $this->getRecords($request)->where('informative', 1)->get()->transform(function (Production $row) {
                return $row->getCollectionData();
            });

            $buildProductsExport = new BuildProductsExport();
            $buildProductsExport->setCollection($records)->setInProccess(true);
            $filename = 'Reporte de produccion en proceso- ' . date('YmdHis');
            // return $buildProductsExport->view();
            return $buildProductsExport->download($filename . '.xlsx');


        }


        public function pdf(Request $request)
        {
            // $records = $this->getData($request);
            $records = $this->getRecords($request)->get()->transform(function (Production $row) {
                return $row->getCollectionData();
            });

            /** @var \Barryvdh\DomPDF\PDF $pdf */
            $pdf = PDF::loadView('production::production.partial.export',
                compact(
                    'records'
                ))
                ->setPaper('a4', 'landscape');


            $filename = 'Reporte de produccion - ' . date('YmdHis');
            return $pdf->stream($filename . '.pdf');
        }
    }
