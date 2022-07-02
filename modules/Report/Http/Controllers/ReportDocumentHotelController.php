<?php

    namespace Modules\Report\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Establishment;
    use Carbon\Carbon;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Database\Query\Builder;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\RedirectResponse;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\View\View;
    use Modules\BusinessTurn\Models\BusinessTurn;
    use Modules\BusinessTurn\Models\DocumentHotel;
    use Modules\Report\Exports\DocumentHotelExport;
    use Modules\Report\Http\Resources\DocumentHotelCollection;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;

    class ReportDocumentHotelController extends Controller
    {
        // use ReportTrait;


        /**
         * @return array
         */
        public function filter()
        {

            $document_types = [];

            $establishments = Establishment::all()->transform(function (Establishment $row) {
                return [
                    'id' => $row->id,
                    'name' => $row->description
                ];
            });

            return compact('document_types', 'establishments');
        }


        /**
         * @return Factory|Application|RedirectResponse|View
         */
        public function index()
        {
            $record = BusinessTurn::where('value', 'hotel')->where('active', true)->first();

            if (!$record) {
                return redirect()->route('tenant.reports.sale_notes.index');
            }


            return view('report::document_hotels.index');
        }

        /**
         * @param Request $request
         *
         * @return DocumentHotelCollection
         */
        public function records(Request $request)
        {
            $records = $this->getRecords($request->all());

            return new DocumentHotelCollection($records->paginate(config('tenant.items_per_page')));
        }

        /**
         * @param $request
         *
         * @return \Illuminate\Database\Eloquent\Builder|Builder|DocumentHotel
         */
        public function getRecords($request)
        {

            $date_start = $request['date_start'];
            $date_end = $request['date_end'];

            return $this->data($date_start, $date_end);

        }

        /**
         * @param $date_start
         * @param $date_end
         *
         * @return \Illuminate\Database\Eloquent\Builder|Builder|DocumentHotel
         */
        private function data($date_start, $date_end)
        {
            return DocumentHotel::SearchByDate($date_start, $date_end)->latest();

            if ($date_start && $date_end) {

                $data = DocumentHotel::where([['date_entry', '>=', $date_start], ['date_exit', '<=', $date_end]])->latest();

            } else {
                $data = DocumentHotel::latest();
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

            $records = $this->getRecords($request->all())->get();

            $documentHotelExport = new DocumentHotelExport();
            $documentHotelExport
                ->records($records)
                ->company($company);

            return $documentHotelExport->download('Reporte_Hoteles_' . Carbon::now() . '.xlsx');

        }

    }
