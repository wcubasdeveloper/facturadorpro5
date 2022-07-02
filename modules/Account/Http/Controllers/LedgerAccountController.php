<?php

    namespace Modules\Account\Http\Controllers;

    use App\CoreFacturalo\Helpers\Functions\FunctionsHelper;
    use App\Http\Controllers\Controller;
    use App\Models\Tenant\Document;
    use App\Models\Tenant\ModelTenant;
    use App\Models\Tenant\Retention;
    use Carbon\Carbon;
    use Illuminate\Database\Query\Builder;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Modules\Account\Exports\LedgerAccountExcelExport;
    use Modules\Account\Models\AccountingLedger;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;


    class LedgerAccountController extends Controller
    {

        /**
         * @param Request $request
         *
         * @return ModelTenant|\Illuminate\Database\Eloquent\Builder|Builder
         */
        public function getRetentions(Request $request)
        {


            $requestArray = $request->all();
            $date_start = $requestArray['date_start'] ?? null;
            $date_end = $requestArray['date_end'] ?? null;
            FunctionsHelper::setDateInPeriod($requestArray, $date_start, $date_end);

            $records = Retention::query();
            if ($request->has('column')) {
                $records->where($request->column, 'like', "%{$request->value}%");


            }
            $records->whereBetween('date_of_issue', [$date_start, $date_end]);

            // ->orderBy('series')
            // ->orderBy('number', 'desc');
            return $records->latest();

        }

        public function index(Request $request)
        {
            return view('account::accounting_ledger.index');

        }

        /**
         * @param Request $request
         *
         * @return Response|BinaryFileResponse
         */
        public function excel(Request $request)
        {
            $dateReport = $request->month_end;
            $records = $this->getData($request);
            $ledgerAccountExcelExport = new LedgerAccountExcelExport();

            $ledgerAccountExcelExport->setRecords($records)->setDateReport($dateReport);
            $filename = 'Libro Mayor '.$dateReport." - " . date('YmdHis');
            // return $ledgerAccountExcelExport->view();
            return $ledgerAccountExcelExport->download($filename . '.xlsx');


        }

        /**
         * @param Request|null $request
         *
         * @return array
         */
        protected function getData(Request $request = null)
        {
            $date = Carbon::now();
            if ($request !== null & $request->has('month_end')) {
                $dateReport = explode('-', $request->month_end);
                $date = Carbon::createFromFormat('Y-m', $dateReport[0] . "-" . $dateReport[1]);
            }

            return AccountingLedger::saveData($date);
        }

        public function record()
        {
            $months = $this->getDatesToReport();
            arsort($months);
            $data = [];

            /**
             * @var Carbon $month
             */

            foreach ($months as $month){
                $temp = [
                    'id'=>$month->format('Y-m'),
                    'description'=>$month->format('Y-m'),
                ];
                $data[]=$temp;
            }
            return ['months' => collect($data)];

        }

        /**
         * Devuelve el rango de meses para los documentos
         *
         * @return array
         */
        protected function getDatesToReport(): array
        {

            /**
             * @var Collection $documents
             * @var Carbon     $documents_min
             * @var Carbon     $documents_max
             * @var array      $months
             */
            $documents = Document::query()->select('date_of_issue')->groupby('date_of_issue')->get();
            $documents_min = $documents->min('date_of_issue');
            $documents_max = $documents->max('date_of_issue');
            // Validar el mes actual para que no haga nada
            $months = [];
            do {
                $d = $documents_min->firstOfMonth();
                $f = $d->format('Y-m');
                $months[$f] = Carbon::createFromFormat('Y-m', $f)->firstOfMonth()->setTime(0, 0, 0);
            } while ($documents_min->addMonth() <= $documents_max);
            return $months;
        }
    }
