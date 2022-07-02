<?php

    namespace Modules\Report\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\User;
    use Barryvdh\DomPDF\Facade as PDF;
    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;
    use Modules\Report\Exports\CommissionExport;
    use Modules\Report\Http\Resources\ReportCommissionCollection;

    class ReportCommissionController extends Controller
    {


        public function filter()
        {

            $document_types = [];

            $establishments = Establishment::all()->transform(function ($row) {
                return [
                    'id' => $row->id,
                    'name' => $row->description
                ];
            });

            $sellers = $this->getSellers();


            return compact('document_types', 'sellers', 'establishments');
        }


        public function index()
        {

            return view('report::commissions.index');
        }

        public function records(Request $request)
        {
            /** @var \Illuminate\Database\Eloquent\Builder  $records */
            $records = $this->getRecords($request->all(), User::class);

            return new ReportCommissionCollection($records->paginate(config('tenant.items_per_page')));
        }


        public function getRecords($request, $model)
        {

            $establishment_id = $request['establishment_id'];
            $period = $request['period'];
            $date_start = $request['date_start'];
            $date_end = $request['date_end'];
            $month_start = $request['month_start'];
            $month_end = $request['month_end'];
            $user_type = $request['user_type'];
            $user_seller_id = $request['user_seller_id'] ?? null;

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

            $records = $this->data($establishment_id, $d_start, $d_end, $model, $user_seller_id, $user_type);

            return $records;

        }


        /**
         * @param     $establishment_id
         * @param     $date_start
         * @param     $date_end
         * @param     $model
         * @param int $user_seller_id
         * @param string $user_type
         *
         * @return Builder
         */
        private function data($establishment_id, $date_start, $date_end, $model, $user_seller_id, $user_type)
        {

            /** @var Builder $data */

            $data = $model::query();

            if ($establishment_id) {
                $data = $data->where('establishment_id', $establishment_id);
            }

            if($user_seller_id){
                $data->where('id', $user_seller_id);
            }

            return $data->latest()->whereTypeUser();

        }


        public function pdf(Request $request)
        {

            $company = Company::first();
            $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
            $records = $this->getRecords($request->all(), User::class)->get();

            $pdf = PDF::loadView('report::commissions.report_pdf', compact("records", "company", "establishment", "request"));

            $filename = 'Reporte_Comision_Vendedor_' . date('YmdHis');

            return $pdf->download($filename . '.pdf');
        }


        public function excel(Request $request)
        {

            $company = Company::first();
            $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

            $records = $this->getRecords($request->all(), User::class)->get();

            $commissionExport = new CommissionExport();
            $commissionExport
                ->records($records)
                ->company($company)
                ->establishment($establishment)
                ->request($request);
            // return $commissionExport->view();
            return $commissionExport->download('Reporte_Comision_Vendedor' . Carbon::now() . '.xlsx');

        }
    }
