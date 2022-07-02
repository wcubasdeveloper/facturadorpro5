<?php

namespace Modules\Finance\Http\Controllers;

use App\Models\Tenant\Company;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\PaymentMethodType;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Expense\Models\ExpenseMethodType;
use Modules\Finance\Exports\PaymentMethodTypeExport;
use Modules\Finance\Traits\FinanceTrait;


class PaymentMethodTypeController extends Controller
{

    use FinanceTrait;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index(){
      $configuration = Configuration::first();
      return view('finance::payment_method_types.index', compact('configuration'));
    }


    public function filter(){

        $payment_types = [];
        $destination_types = [];

        return compact('payment_types', 'destination_types');
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function records(Request $request) {
        return $this->getRecords($request->all());
    }

    /**
     * @param array $request
     *
     * @return array
     */
    public function getRecords($request = []) {

        $data_of_period = $this->getDatesOfPeriod($request);

        $params = (object)[
            'date_start' => $data_of_period['d_start'],
            'date_end'   => $data_of_period['d_end'],
        ];

        $payment_method_types = PaymentMethodType::whereFilterPayments($params)->get();
        $expense_method_types = ExpenseMethodType::whereFilterPayments($params)->get();

        $records_by_pmt = $this->getRecordsByPaymentMethodTypes($payment_method_types);
        $records_by_emt = $this->getRecordsByExpenseMethodTypes($expense_method_types);
        $totals = $this->getTotalsPaymentMethodType($records_by_pmt, $records_by_emt);

        return [
            'records' => $records_by_pmt->merge($records_by_emt),
            'totals'  => $totals,
        ];

    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function pdf(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecords($request->all());
        $pdf = PDF::loadView('finance::payment_method_types.report_pdf', compact("records", "company", "establishment"));
        $filename = 'Metodos_de_pago_'.date('YmdHis');
        return $pdf->download($filename.'.pdf');
    }


    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function excel(Request $request) {

        $company = Company::first();
        $establishment = ($request->establishment_id) ? Establishment::findOrFail($request->establishment_id) : auth()->user()->establishment;
        $records = $this->getRecords($request->all());

        $payment = new PaymentMethodTypeExport();
        $payment->records($records)
                ->company($company)
                ->establishment($establishment);
        return $payment->download('Metodos_de_pago_'.Carbon::now().'.xlsx');

    }

}
