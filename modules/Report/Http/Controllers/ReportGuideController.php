<?php

    namespace Modules\Report\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\Tenant\DispatchItemCollection;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\Dispatch;
    use App\Models\Tenant\DispatchItem;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\User;
    use Barryvdh\DomPDF\Facade as PDF;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Modules\Report\Exports\GuidesConsolidatedExport;
    use Modules\Report\Exports\GuidesConsolidatedTotalExport;
    use Modules\Report\Traits\ReportTrait;


    /**
     * Class ReportGuideController
     *
     * @package Modules\Report\Http\Controllers
     * @mixin Controller
     */
    class ReportGuideController extends Controller {
        use ReportTrait;

        protected $configuration;

        /**
         * ReportGuideController constructor.
         *
         */
        public function __construct() {
            $this->configuration = Configuration::first();
        }

        /**
         * @return array
         */
        public function filter() {

            $document_types = [];
            $dispatch = Dispatch::where(function ($q) {
                $q->wherenotnull('customer_id');
                $q->wherenotnull('user_id');
                $q->wherenotnull('establishment_id');
            });
            $dispatch_customer_id = $dispatch->select('customer_id')->get()
                                             ->pluck('customer_id');
            $dispatch_user_id = $dispatch->select('user_id')->get()->pluck('user_id');

            $dispatch_establishment_id = $dispatch->select('establishment_id')->get()
                                                  ->pluck('establishment_id');
            $dispatch_item_ids = DispatchItem::wherenotnull('item_id')->select('item_id')->get()->pluck('item_id');

            $customers = Person::with('addresses')
                               ->whereType('customers')
                               ->wherein('id', $dispatch_customer_id)
                               ->whereIsEnabled()
                               ->orderBy('name')
                               ->take(20)
                               ->get()->transform(function ($row) {
                    /** @var Person $row */
                    return $row->getCollectionData();
                });
            $users = User::wherein('id', $dispatch_user_id)->get();

            $items = $this->getItems('items', $dispatch_item_ids)->get();
            $establishments = Establishment::wherein('id', $dispatch_establishment_id)->get();
            $web_platforms = $this->getWebPlatforms();

            return compact('document_types', 'establishments', 'items', 'web_platforms', 'customers', 'users');
        }

        /**
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
         */
        public function index() {

            $configuration = $this->getConfiguration();
            return view('report::guides.index', compact('configuration'));
        }

        /**
         * @return \App\Models\Tenant\Configuration|\Illuminate\Database\Eloquent\Model|object|null
         */
        public function getConfiguration() {
            return $this->configuration;
        }

        /**
         * @param \App\Models\Tenant\Configuration|\Illuminate\Database\Eloquent\Model|object|null $configuration
         *
         * @return ReportGuideController
         */
        public function setConfiguration($configuration) {
            $this->configuration = $configuration;
            return $this;
        }

        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return \App\Http\Resources\Tenant\DispatchItemCollection
         */
        public function records(Request $request) {
            $records = $this->getRecordsDispachesItem($request->all());

            return new DispatchItemCollection($records->paginate(config('tenant.items_per_page')));
        }

        /**
         * @param array $request
         *
         * @return \App\Models\Tenant\DispatchItem
         */
        public function getRecordsDispachesItem($request = []) {
            $document_type_id = $request['document_type_id'];
            $period = $request['period'];
            $date_start = $request['date_start'];
            $date_end = $request['date_end'];
            $month_start = $request['month_start'];
            $month_end = $request['month_end'];
            $item_id = isset($request['item_id']) ? (int)$request['item_id'] : 0;
            $web_platform_id = isset($request['web_platform_id']) ? (int)$request['web_platform_id'] : 0;
            $user_id = isset($request['user_id']) ? (int)$request['user_id'] : 0;
            $customer_id = isset($request['customer_id']) ? (int)$request['customer_id'] : 0;
            $establishment_id = isset($request['establishment_id']) ? (int)$request['establishment_id'] : 0;

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
                    // $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
                    break;
                case 'between_months':
                    $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                    $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
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
            $dispatch = DispatchItem::JoinDispatch()->JoinItem()
                                    ->whereBetween('dispatches.date_of_shipping', [$d_start, $d_end]);
            if ($customer_id != 0) {
                $dispatch->where('dispatches.customer_id', $customer_id);
            }
            if ($user_id != 0) {
                $dispatch->where('dispatches.user_id', $user_id);
            }
            if ($establishment_id != 0) {
                $dispatch->where('dispatches.establishment_id', $establishment_id);
            }
            if ($item_id != 0) {
                $dispatch->where('dispatch_items.item_id', $item_id);
            }


            if (isset($request['min']) && isset($request['max'])) {
                $min = (int)$request['min'];
                $max = (int)$request['max'];
                if($max < $min){
                    $min = (int)$request['max'];
                    $max = (int)$request['min'];
                }
                if($min !== 0 && $max !== 0) {
                    $dispatch->whereBetween('dispatches.number', [$min, $max]);
                }
            }


            /** @var \Illuminate\Support\Collection $ids */
            $ids = $dispatch->select('dispatch_items.id as id')->get()->pluck('id')->unique();

            return DispatchItem::wherein('id', $ids)->orderBy('dispatch_id', 'desc');
        }

        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return mixed
         */
        public function pdf(Request $request) {

            $company = Company::first();
            $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id)
                : auth()->user()->establishment;
            $records = $this->getRecordsDispachesItem($request->all())->get();
            $params = $request->all();
            self::setParams($params);
            /*
                    return view('report::guides.report_pdf',
                                compact('records', 'company', 'establishment', 'params'));
                    */

            $pdf = PDF::loadView('report::guides.report_pdf',
                                 compact('records', 'company', 'establishment', 'params'));

            $filename = 'Reporte_Consolidado_Items_Guias_'.date('YmdHis');

            return $pdf->download($filename.'.pdf');
        }

        /**
         * @param $params
         */
        protected static function setParams(&$params) {

            if (isset($params['user_id']) && !empty($params['user_id'])) {
                $params['seller_id'] = (int)$params['user_id'];
            }
            if (isset($params['customer_id']) && !empty($params['customer_id'])) {
                $params['person_id'] = (int)$params['customer_id'];
            }
        }

        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return mixed
         */
        public function pdfTotals(Request $request) {

            $company = Company::first();
            $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id)
                : auth()->user()->establishment;
            $records = $this->totalsByItem($request)->sortBy('item_id');

            $params = $request->all();
            self::setParams($params);
            /*
            return view('report::guides.report_pdf_totals',
                        compact('records', 'company', 'establishment', 'params'));
            */
            $pdf = PDF::loadView('report::guides.report_pdf_totals',
                                 compact('records', 'company', 'establishment', 'params'));

            $filename = 'Reporte_Consolidado_Items_Guias_Totales_'.date('YmdHis');

            return $pdf->download($filename.'.pdf');
        }

        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Support\Collection
         */
        public function totalsByItem(Request $request) {

            $records = $this->getRecordsDispachesItem($request->all())->get()->groupBy('item_id');

            return $records->map(function ($row, $key) {

                return [
                    'item_id'           => $key,
                    'item_internal_id'  => $row->first()->relation_item->internal_id,
                    'item_unit_type_id' => $row->first()->relation_item->unit_type_id,
                    'item_description'  => $row->first()->item->description,
                    'quantity'          => number_format($row->sum('quantity'), 4, '.', ''),
                ];
            });

        }

        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
         */
        public function excel(Request $request) {

            $company = Company::first();
            $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id)
                : auth()->user()->establishment;

            $records = $this->getRecordsDispachesItem($request->all())->get();
            $params = $request->all();
            self::setParams($params);
            $filename = 'Reporte_Consolidado_Items_Guias_'.date('YmdHis');

            return (new GuidesConsolidatedExport())
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->params($params)
                ->download($filename.'.xlsx');

        }

        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
         */
        public function excelTotals(Request $request) {

            $company = Company::first();
            $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id)
                : auth()->user()->establishment;
            $records = $this->totalsByItem($request)->sortBy('item_id');
            $params = $request->all();
            self::setParams($params);
            $filename = 'Reporte_Consolidado_Items_Guias_Totales_'.date('YmdHis');

            return (new GuidesConsolidatedTotalExport())
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->params($params)
                ->download($filename.'.xlsx');

        }

    }
