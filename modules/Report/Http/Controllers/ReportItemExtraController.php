<?php

    namespace Modules\Report\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\DocumentItem;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\ItemMovement;
    use App\Models\Tenant\PurchaseItem;
    use Carbon\Carbon;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\View\View;
    use Modules\Report\Exports\ItemExport;
    use Modules\Report\Http\Resources\ItemCollection;
    use Modules\Report\Traits\ReportTrait;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;


    /**
     *
     */
    class ReportItemExtraController extends Controller
    {
        use ReportTrait;

        /**
         * @return array
         */
        public function filter()
        {

            $document_types = [];
            $items = $this->getItems('items');
            $establishments = [];
            $web_platforms = $this->getWebPlatforms();

            return compact('document_types', 'establishments', 'items', 'web_platforms');
        }


        /**
         * @return Factory|Application|View
         */
        public function index()
        {


            return view('report::items.index_extra');
        }

        /**
         * @param Request $request
         *
         * @return ItemCollection
         */
        public function records(Request $request)
        {


            $data = [
                'establishment_id' => $request->has('establishment_id') ? $request->establishment_id : null,
                'item_id' => $request->has('item_id') ? $request->item_id : null,
                'document_type_id' => $request->has('document_type_id') ? $request->document_type_id : null,
                'period' => $request->has('period') ? $request->period : 'month',
                'web_platform_id' => $request->has('web_platform_id') ? $request->web_platform_id : null,
                'date_start' => $request->has('date_start') ? $request->date_start : null,
                'date_end' => $request->has('date_end') ? $request->date_end : null,
                'month_start' => $request->has('month_start') ? $request->month_start : null,
                'month_end' => $request->has('month_end') ? $request->month_end : null,
                'extra' => $request->has('extra') ? [
                    'colors' => $request->has('extra.colors') ? $request->extra['colors'] : null,
                    'CatItemUnitsPerPackage' => $request->has('extra.CatItemUnitsPerPackage') ? $request->extra['CatItemUnitsPerPackage'] : null,
                    'CatItemMoldProperty' => $request->has('extra.CatItemMoldProperty') ? $request->extra['CatItemMoldProperty'] : null,
                    'CatItemUnitBusiness' => $request->has('extra.CatItemUnitBusiness') ? $request->extra['CatItemUnitBusiness'] : null,
                    'CatItemStatus' => $request->has('extra.CatItemStatus') ? $request->extra['CatItemStatus'] : null,
                    'CatItemPackageMeasurement' => $request->has('extra.CatItemPackageMeasurement') ? $request->extra['CatItemPackageMeasurement'] : null,
                    'CatItemMoldCavity' => $request->has('extra.CatItemMoldCavity') ? $request->extra['CatItemMoldCavity'] : null,
                    'CatItemProductFamily' => $request->has('extra.CatItemProductFamily') ? $request->extra['CatItemProductFamily'] : null,
                    'CatItemSize' => $request->has('extra.CatItemSize') ? $request->extra['CatItemSize'] : null,
                ] : [],
            ];
            $records = $this->getRecordsItems($data, DocumentItem::class);

            return new ItemCollection($records->paginate(config('tenant.items_per_page')));
        }


        /**
         * @param $request
         * @param $model
         *
         * @return Builder
         */
        public function getRecordsItems($request, $model)
        {

            // dd($request['period']);
            $document_type_id = isset($request['document_type_id']) ? $request['document_type_id'] : null;
            $establishment_id = isset($request['establishment_id']) ? $request['establishment_id'] : null;
            $period = isset($request['period']) ? $request['period'] : null;
            $date_start = isset($request['date_start']) ? $request['date_start'] : null;
            $date_end = isset($request['date_end']) ? $request['date_end'] : null;
            $month_start = isset($request['month_start']) ? $request['month_start'] : null;
            $month_end = isset($request['month_end']) ? $request['month_end'] : null;
            $item_id = isset($request['item_id']) ? $request['item_id'] : null;
            $web_platform_id = isset($request['web_platform_id']) ? $request['web_platform_id'] : null;
            $extra = isset($request['extra']) ? $request['extra'] : [];
            $type = isset($request['type']) ? $request['type'] : null;
            if ($type == 'purchase') {
                $model = PurchaseItem::class;
            }

            $d_start = null;
            $d_end = null;

            /** @todo: Eliminar periodo, fechas y cambiar por

            $date_start = $request['date_start'];
            $date_end = $request['date_end'];
            \App\CoreFacturalo\Helpers\Functions\FunctionsHelper\FunctionsHelper::setDateInPeriod($request, $date_start, $date_end);
             */
            switch ($period) {
                case 'month':
                    $d_start = Carbon::parse($month_start . '-01')->format('Y-m-d');
                    $d_end = Carbon::parse($month_start . '-01')->endOfMonth()->format('Y-m-d');
                    // $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                    break;
                case 'between_months':
                    $d_start = Carbon::parse($month_start . '-01')->format('Y-m-d');
                    $d_end = Carbon::parse($month_end . '-01')->endOfMonth()->format('Y-m-d');
                    break;
                case 'date':
                    $d_start = $date_start;
                    $d_end = $date_start;
                    // $d_end = $date_end;
                    break;
                case 'between_dates':
                    $d_start = $date_start;
                    $d_end = $date_end;
                    break;
            }

            $records = $this->dataItems($document_type_id, $establishment_id, $d_start, $d_end, $item_id, $model, $web_platform_id, $extra);

            return $records;

        }


        /**
         * @param      $document_type_id
         * @param      $establishment_id
         * @param      $date_start
         * @param      $date_end
         * @param      $item_id
         * @param      $model
         * @param      $web_platform_id
         * @param null $type
         *
         * @return Builder
         */
        private function dataItems(
            $document_type_id,
            $establishment_id,
            $date_start,
            $date_end,
            $item_id,
            $model,
            $web_platform_id,
            $extra = [])
        {

            $data = $model::where('item_id', $item_id);

            $movement = ItemMovement::getElementItemIdFromClass($model,$item_id,$extra);

            if (count($movement)> 0) {
                $data->wherein('id',$movement);
            }
            if ($model !== PurchaseItem::class) {

                $data->whereHas('document', function ($query) use ($date_start, $date_end) {
                    $query
                        ->whereBetween('date_of_issue', [$date_start, $date_end])
                        ->whereIn('document_type_id', ['01', '03'])
                        ->whereIn('state_type_id', ['01', '03', '05', '07', '13'])
                        ->latest()
                        ->whereTypeUser();
                });
                if ($web_platform_id) {

                    $data = $data->whereHas('relation_item', function ($q) use ($web_platform_id) {
                        $q->where('web_platform_id', $web_platform_id);
                    });

                }
            } else {
                $data->whereHas('purchase', function ($query) use ($date_start, $date_end) {
                    /** @var Builder $query */
                    $query
                        ->whereBetween('date_of_due', [$date_start, $date_end])
                        //->whereIn('document_type_id', ['01','03'])
                        ->WhereStateTypeAccepted()
                        ->latest()
                        ->whereTypeUser();
                });
            }

            return $data;

        }


        /**
         * @param Request $request
         *
         * @return Response|BinaryFileResponse
         */
        public function excel(Request $request)
        {


            $company = Company::first();
            $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
            if ($request->has('type') && $request->type === 'purchase') {
                $model = PurchaseItem::class;
            } else {
                $model = DocumentItem::class;
            }
            $records = $this->getRecordsItems($request->all(), $model)->get();
            $itemExport = new ItemExport();
            $fileName = 'Reporte_Ventas_por_Producto_' . Carbon::now() . '.xlsx';
            if ($request->has('type') && $request->type === 'purchase') {
                $itemExport->setType($request->type);
                $fileName = 'Reporte_Compras_por_Producto_' . Carbon::now() . '.xlsx';

            }

            $data = $itemExport
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->setWithExtraData(true);
            // return $data->view();
            return $data->download($fileName);

        }
    }
