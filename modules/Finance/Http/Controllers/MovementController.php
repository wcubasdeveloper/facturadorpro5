<?php

namespace Modules\Finance\Http\Controllers;

use App\Models\Tenant\Cash;
use App\Models\Tenant\Company;
use App\Models\Tenant\Establishment;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Finance\Exports\MovementExport;
use Modules\Finance\Http\Resources\MovementCollection;
use Modules\Finance\Models\GlobalPayment;
use Modules\Finance\Traits\FinanceTrait;
use Modules\Pos\Models\CashTransaction;
use App\Models\Tenant\DownloadTray;
use Modules\Finance\Jobs\ProcessMovementsReport;
use App\Models\System\Client;

class MovementController extends Controller
{
    
    use FinanceTrait;

    public function index(){

        $isMovements = 1;
        return view('finance::movements.index',compact('isMovements'));
    }

    public function indexTransactions(){

        $isMovements = 0;
        return view('finance::movements.index',compact('isMovements'));
    }


    public function records(Request $request)
    {
        ini_set('max_execution_time', 0);
        $records = $this->getRecords($request->all(), GlobalPayment::class);
        $records->orderBy('id');

        if($request->has('paginate')){
            return new MovementCollection($records->paginate(1000));
            //$collec
        }
        return new MovementCollection($records->paginate(config('tenant.items_per_page')));

    }

    /**
     * @param array $request
     * @param  GlobalPayment::class  $model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getRecords($request, $model){

        $data_of_period = $this->getDatesOfPeriod($request);
        $payment_type = $request['payment_type'];
        $destination_type = $request['destination_type'];
        $last_cash_opening = $request['last_cash_opening'];

        $params = (object)[
            'date_start' => $data_of_period['d_start'],
            'date_end' => $data_of_period['d_end'],
        ];


        $records = $model::whereFilterPaymentType($params);

        if($last_cash_opening == 'true'){

            $cash =  Cash::where([['user_id',auth()->user()->id],['state',true]])->first();

            if($cash){

                /*$last_cash = GlobalPayment::wherePaymentType(CashTransaction::class)
                                            ->whereDestinationType(Cash::class)
                                            ->where('destination_id', $cash->id)
                                            ->latest()
                                            ->first();*/
                /** @var \Illuminate\Database\Eloquent\Builder  $records */

                return $records->whereDestinationType(Cash::class)
                                ->where('destination_id', $cash->id)->latest();

            }


        }
        /** @var \Illuminate\Database\Eloquent\Builder  $records */
        return $records->latest();
    }


    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecords($request->all(), GlobalPayment::class)->get();

        $pdf = PDF::loadView('finance::movements.report_pdf', compact("records", "company", "establishment"))->setPaper('a4', 'landscape');;

                $filename = 'Reporte_Movimientos_'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }


    public function postPdf(Request $request){
        $records = $request->data;
        $order = $request->order;
        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;

        $pdf = PDF::loadView('finance::movements.new_report_pdf',
                             compact('records', 'company', 'establishment'))
                  ->setPaper('a4', 'landscape');;


        $filename = 'Reporte_Movimientos_'.date('YmdHis');

        return $pdf->download($filename.'.pdf');
    }

    public function postExcel(Request $request) {
        
        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id)
            : auth()->user()->establishment;
        $records = $request->data;
        $MovementExport = new MovementExport();
        $MovementExport
            ->records($records)
            ->company($company)
            ->setNewFormat(true)
            ->establishment($establishment);

        return $MovementExport->download('Reporte_Movimientos_'.Carbon::now().'.xlsx');
    }
    public function excel(Request $request) {

        /*$params = (object) array_merge( $request->all(), ['user_id' => auth()->user()->id]);
        return json_encode($params);*/

        $company = Company::active();
        $client = Client::where('number', $company->number)->first();
        $website_id = $client->hostname->website_id;

       // $records = $this->getRecords($request->all(), GlobalPayment::class);
        //$records->orderBy('id');

        $tray = DownloadTray::create([
            'user_id' => auth()->user()->id,
            'module' => 'INVENTORY',
            'path' => $request->path,
            'format' => 'xlsx',
            'date_init' => date('Y-m-d H:i:s'),
            'type' => 'Reporte Movimientos ingresos-egresos'
        ]);

        $params = (object)array_merge($request->all(), ['user_id' => auth()->user()->id, 'type' => auth()->user()->type, 'establishment_id' => auth()->user()->establishment_id]);

        ProcessMovementsReport::dispatch($params, $tray->id, $website_id);
        // ProcessMovementsReport::dispatch($params, $tray->id, $website_id)->onQueue('process_movements_report');

        return [
            'success' => true,
            'message' => 'El reporte se esta procesando; puede ver el proceso en bandeja de descargas.'
        ];

        /*$company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecords($request->all(), GlobalPayment::class)->get();

        $movementExport  = new MovementExport();
        $movementExport
            ->records($records)
            ->company($company)
            ->establishment($establishment);
        return $movementExport->view();
        return $movementExport->download('Reporte_Movimientos_'.Carbon::now().'.xlsx');*/

    }

}
