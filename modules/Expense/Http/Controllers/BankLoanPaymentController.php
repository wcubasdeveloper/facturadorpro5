<?php

    namespace Modules\Expense\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\Tenant\PaymentMethodType;
    use Illuminate\Support\Facades\DB;
    use Modules\Expense\Http\Requests\BankLoanPaymentRequest;
    use Modules\Expense\Http\Resources\BankLoanPaymentCollection;
    use Modules\Expense\Models\BankLoan;
    use Modules\Expense\Models\BankLoanPayment;
    use Modules\Finance\Traits\FilePaymentTrait;
    use Modules\Finance\Traits\FinanceTrait;

    class BankLoanPaymentController extends Controller
    {

        use FilePaymentTrait;
        use FinanceTrait;

        public function records($expense_id)
        {
            $records = BankLoanPayment::where('bank_loan_id', $expense_id)->get();

            return new BankLoanPaymentCollection($records);
        }

        public function tables()
        {
            return [
                 'payment_method_types' => PaymentMethodType::NotCash()->NotCredit()->get(),

                'payment_destinations' => $this->getPaymentDestinations()
            ];
        }

        public function Expense($expense_id)
        {
            $expense = BankLoan::find($expense_id);

            $total_paid = collect($expense->payments)->sum('payment');
            $total = $expense->total;
            $total_difference = round($total - $total_paid, 2);

            return [
                'number_full' => $expense->number,
                'total_paid' => $total_paid,
                'total' => $total,
                'total_difference' => $total_difference,
                'payment_destination_id'=> (int)$expense->bank_account_id
            ];

        }


        public function store(BankLoanPaymentRequest $request)
        {
            // return $this->stillOnWork();

            $id = $request->input('id');


            DB::connection('tenant')->transaction(function () use ($id, $request) {

                $record = BankLoanPayment::firstOrNew(['id' => $id]);

                $record->fill($request->all());


                $record->save();
                $this->createGlobalPayment($record, $request->all());
                // $this->saveFiles($record, $request, 'expenses');

            });

            return [
                'success' => true,
                'message' => ($id) ? 'Pago editado con éxito' : 'Pago registrado con éxito'
            ];
        }

        public function destroy($id)
        {
            return $this->stillOnWork();
            $item = BankLoanPayment::findOrFail($id);
            $item->delete();

            return [
                'success' => true,
                'message' => 'Pago eliminado con éxito'
            ];
        }

        public function stillOnWork(){
            return [
                'success' => true,
                'message' => 'Aun no implementado'
            ];
        }


    }
