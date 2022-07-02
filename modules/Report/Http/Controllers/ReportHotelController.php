<?php
//report_hotels
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
    use Modules\Hotel\Models\HotelFloor;
    use Modules\Hotel\Models\HotelRent;
    use Modules\Hotel\Models\HotelRoom;
    use Modules\Report\Exports\DocumentHotelExport;
    use Modules\Report\Exports\ReportHotelExport;
    use Modules\Report\Http\Resources\DocumentHotelCollection;
    use Modules\Report\Http\Resources\RentHotelCollection;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;

    class ReportHotelController extends Controller
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
            return view('report::report_hotels.index');

            $rooms = $this->getRooms();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'rooms'   => $rooms,
                ], 200);
            }
            $floors = HotelFloor::where('active', true)
                ->orderBy('description')
                ->get();

            $roomStatus = HotelRoom::$status;

            return view('hotel::report_hotels.index', compact('rooms', 'floors', 'roomStatus'));
        }

        /**
         * @param Request $request
         *
         * @return RentHotelCollection
         */
        public function records(Request $request)
        {
            $records = $this->getRecords($request->all());

            return new RentHotelCollection($records->paginate(config('tenant.items_per_page')));
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
         * @return \Illuminate\Database\Eloquent\Builder|Builder|HotelRent
         */
        private function data($date_start, $date_end)
        {
            return HotelRent::SearchByDate($date_start,$date_end)->latest();



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

            $documentHotelExport = new ReportHotelExport();
            $documentHotelExport
                ->records($records)
                ->company($company);

            return $documentHotelExport->download('Reporte_Hoteles_' . Carbon::now() . '.xlsx');

        }




        /**
         * Devuelve informacion de cuartos disponibles
         *
         * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|\Modules\Hotel\Models\HotelRoom[]
         */
        private function getRooms()
        {
            $rooms = HotelRoom::with('category', 'floor', 'rates');

            if (request('hotel_floor_id')) {
                $rooms->where('hotel_floor_id', request('hotel_floor_id'));
            }
            if (request('status')) {
                $rooms->where('status', request('status'));
            }

            $rooms->orderBy('name');
            return $rooms->get()->each(function ($room) {
                if ($room->status === 'OCUPADO') {
                    $rent = HotelRent::where('hotel_room_id', $room->id)
                        ->orderBy('id', 'DESC')
                        ->first();
                    $room->rent = $rent;
                } else {
                    $room->rent = [];
                }

                return $room;
            });
        }
    }
