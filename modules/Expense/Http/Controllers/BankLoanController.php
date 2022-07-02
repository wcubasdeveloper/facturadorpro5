<?php

    namespace Modules\Expense\Http\Controllers;

    use App\CoreFacturalo\Requests\Inputs\Common\BankInput;
    use App\Models\Tenant\Bank;
    use App\Models\Tenant\BankAccount;
    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Establishment;
    use App\Models\Tenant\Person;
    use Carbon\Carbon;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Database\Query\Builder;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\Routing\Controller;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Str;
    use Illuminate\View\View;
    use Modules\Expense\Exports\ExpenseExport;
    use Modules\Expense\Http\Requests\BankLoanRequest;
    use Modules\Expense\Http\Resources\BankLoanCollection;
    use Modules\Expense\Http\Resources\BankLoanResource;
    use Modules\Expense\Models\BankLoan;
    use Modules\Expense\Models\BankLoanReason;
    use Modules\Expense\Models\BankLoanType;
    use Modules\Expense\Models\ExpenseMethodType;
    use Modules\Finance\Traits\FinanceTrait;
    use Symfony\Component\HttpFoundation\BinaryFileResponse;
    use Throwable;

    /**
     * Class BankLoanController
     *
     * @mixin Controller
     * @package Modules\Expense\Http\Controllers
     */
    class BankLoanController extends Controller
    {
        use FinanceTrait;

        /**
         * @return Factory|Application|View
         */
        public function index()
        {
            return view('expense::bank_loans.index');
        }

        /**
         * @param null $id
         *
         * @return Factory|Application|View
         */
        public function create($id = null)
        {
            return view('expense::bank_loans.form', compact('id'));
        }

        /**
         * @return string[]
         */
        public function columns()
        {
            return [
                'date_of_issue' => 'Fecha de emisión',
                'number' => 'Número',
            ];
        }

        /**
         * @param Request $request
         *
         * @return BankLoanCollection
         */
        public function records(Request $request)
        {
            $records = BankLoan::
            where($request->column, 'like', "%{$request->value}%")
                ->whereTypeUser()
                ->latest();
            return new BankLoanCollection($records->paginate(config('tenant.items_per_page')));
        }

        /**
         * @return array
         */
        public function tables()
        {
            /** @var Collection $currenys */
            $currenys = collect([]);
            $allAccounts = collect([]);
            $banks = Bank::where('active', 1)
                ->whereHas('bank_accounts')
                ->get()
                ->transform(function (Bank $row) use (&$currenys,&$allAccounts) {
                    $data = $row->toArray();
                    $accounts = $row->bank_accounts;

                    $data['accounts'] = $accounts;
                    /** @var Collection $col */
                    $currency_types = $accounts->pluck('currency_type_id');
                    $data['currency_types'] = $currency_types;
                    $temp = [];
                    /** @var BankAccount $bank_accounts */
                    foreach ($accounts as $bank_accounts) {
                        $currenys->push($bank_accounts->currency_type_id);
                        $allAccounts->push($bank_accounts);
                        // $temp[] = $currency;
                    }

                    // $data['description'] .= " - " . implode(' - ', $temp);
                    return $data;
                });
            $establishment = Establishment::WithMyEstablishment()->first();
            $currency_types = CurrencyType::whereActive()->whereIn('id', $currenys)->get();
            $bank_loan_types = BankLoanType::get();
            $bank_loan_method_types = ExpenseMethodType::all(); // Por hacer
            $bank_loan_reasons = BankLoanReason::all();
            $payment_destinations = $this->getBankAccounts();
            $bank_loan_method_types = $payment_destinations;
            $accounts = $allAccounts;
            return compact(
                'accounts',
                'establishment',
                'currency_types',
                'banks',
                'bank_loan_types',
                'bank_loan_method_types',
                'bank_loan_reasons',
                'payment_destinations');
        }

        /**
         * @param $table
         *
         * @return Person[]|array|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|Builder[]|Collection
         */
        public function table($table)
        {
            switch ($table) {
                case 'banks':
                    return Bank::where('active',1)->orderBy('description')->get()->transform(function (Bank $row){
                        return $row->toArray();
                });
                    break;
                case 'suppliers':
                    $suppliers = Person::whereType('suppliers')->orderBy('name')->get()->transform(function ($row) {
                        return [
                            'id' => $row->id,
                            'description' => $row->number . ' - ' . $row->name,
                            'name' => $row->name,
                            'number' => $row->number,
                            'identity_document_type_id' => $row->identity_document_type_id,
                            'identity_document_type_code' => $row->identity_document_type->code
                        ];
                    });
                    return $suppliers;
                    break;
                default:
                    return [];
                    break;
            }
        }

        /**
         * @param $id
         *
         * @return BankLoanResource
         */
        public function record($id)
        {
            $record = new BankLoanResource(BankLoan::findOrFail($id));
            return $record;
        }

        /**
         * @param BankLoanRequest $request
         *
         * @return array
         * @throws Throwable
         */
        public function store(BankLoanRequest $request)
        {
             // dd($request->all());
            $data = self::merge_inputs($request);


            $loan = DB::connection('tenant')->transaction(function () use ($data) {
                // $doc = BankLoan::create($data);
                $doc = BankLoan::updateOrCreate(['id' => $data['id']], $data);
                $doc->items()->delete();
                foreach ($data['items'] as $row) {
                    $doc->items()->create($row);
                }
                $this->deleteAllPayments($doc->payments);
                foreach ($data['payments'] as $row) {
                    $record_payment = $doc->payments()->create($row);

                    if (isset($row['bank_loan_method_type_id']) && $row['bank_loan_method_type_id'] == 1) {
                        $row['payment_destination_id'] = 'cash';
                    }
                    $this->createGlobalPayment($record_payment, $row);
                }
                foreach($data['fee'] as $row){
                    $row['data'] = $row['date'] ?? $doc->date_of_issue;
                    $row['currency_type_id'] = $row['currency_type_id'] ?? $doc->currency_type_id;
                    $doc->fee()->create($row);
                }
                return $doc;
            });
            return [
                'success' => true,
                'data' => [
                    'id' => $loan->id,
                ],
            ];
        }

        /**
         * @param $inputs
         *
         * @return mixed
         */
        public static function merge_inputs(BankLoanRequest $inputs)
        {
            $company = Company::active();
            $bank = $inputs->bank_id;
            if($inputs->has('bank_account_id') && $bank=== null){
                $bankAccount = BankAccount::find($inputs->bank_account_id);
                $bank = $bankAccount->bank_id;
            }
            $values = [
                'user_id' => auth()->id(),
                'state_type_id' => $inputs['id'] ? $inputs['state_type_id'] : '05',
                'soap_type_id' => $company->soap_type_id,
                'external_id' => $inputs['id'] ? $inputs['external_id'] : Str::uuid()->toString(),
                'bank' => BankInput::set($bank),
                'bank_id'=>$bank
            ];
            $inputs->merge($values);
            return $inputs->all();
        }

        /**
         * @param $record
         *
         * @return array
         */
        public function voided($record)
        {
            try {
                $expense = BankLoan::findOrFail($record);
                $expense->state_type_id = 11;
                $expense->save();
                return [
                    'success' => true,
                    'data' => [
                        'id' => $expense->id,
                    ],
                    'message' => 'Credito bancario anulado exitosamente',
                ];
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'data' => [
                        'id' => $record,
                    ],
                    'message' => 'Falló al anular',
                ];
            }
        }

        /**
         * @param Request $request
         *
         * @return Response|BinaryFileResponse
         */
        public function excel(Request $request)
        {
            $records = BankLoan::where($request->column, 'like', "%{$request->value}%")
                ->whereTypeUser()
                ->latest()
                ->get();
            // dd($records);
            $establishment = auth()->user()->establishment;
            $balance = new ExpenseExport();
            $balance
                ->records($records)
                ->establishment($establishment);
            // return $balance->View();
            return $balance->download('Expense_' . Carbon::now() . '.xlsx');
        }
    }
