<?php

    namespace Modules\Suscription\Http\Controllers;

    use App\Http\Controllers\SearchCustomerController;
    use Carbon\Carbon;
    use Illuminate\Http\Request;
    use Modules\Suscription\Http\Requests\PaymentsSuscriptionRequest;
    use Modules\Suscription\Http\Resources\UserRelSuscriptionPlansCollection;
    use Modules\Suscription\Http\Resources\UserRelSuscriptionPlansResource;
    use Modules\Suscription\Models\Tenant\CatPeriod;
    use Modules\Suscription\Models\Tenant\SuscriptionPlan;
    use Modules\Suscription\Models\Tenant\UserRelSuscriptionPlan;

    class PaymentsSuscriptionController extends SuscriptionController
    {
        public function Columns()
        {
            return [
                'cat_period_id' => 'Periodos',
                'name' => 'Descripción',
                'description' => 'Nombre',
            ];

        }

        public function Record(Request $request)
        {

            return new UserRelSuscriptionPlansResource(UserRelSuscriptionPlan::findOrFail($request->person));
        }

        public function Records(Request $request)
        {
            $records = UserRelSuscriptionPlan::query();
            if ($request->has('column') && !empty($request->column)) {
                $records->where($request->column, 'like', "%{$request->value}%");
            }
            /** @var \Illuminate\Database\Query\Builder $records */
            // $records->orderBy('name');
            // ->where('type', $type)
            return new UserRelSuscriptionPlansCollection($records->paginate(config('tenant.items_per_page')));
        }

        public function Tables()
        {

            $customers = SearchCustomerController::getSuscriptionCustomers();
            $periods = CatPeriod::where('active', 1)->get();
            $startDate = Carbon::createFromFormat('Y-m-d', '2022-01-01')->format('Y-m-d');
            $plans = SuscriptionPlan::where('id', '!=', 0)
                ->get()
                ->transform(function ($row) {
                    return $row->getCollectionData();
                });

            return compact(
                'periods',
                'customers',
                'startDate',
                'plans'

            );

        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function destroy($id)
        {

            $record = UserRelSuscriptionPlan::findOrFail($id);
            $record->delete();

            return [
                'success' => true,
                'message' => 'Matrícula eliminada con éxito'
            ];

        }


        /**
         * Show the form for editing the specified resource.
         *
         * @param int $id
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function edit($id)
        {
            return view('suscription::edit');
        }

        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function index()
        {
            return view('suscription::payments.index');
        }

        /**
         * Show the specified resource.
         *
         * @param int $id
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function show($id)
        {
            return view('suscription::show');
        }

        public function searchCustomer(Request $request)
        {
            $customers = SearchCustomerController::getSuscriptionCustomers($request);

            return ['customers' => $customers];

        }

        /**
         * Update the specified resource in storage.
         *
         * @param PaymentsSuscriptionRequest $request
         * @param int                        $id
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function update(PaymentsSuscriptionRequest $request, $id)
        {
            //
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param PaymentsSuscriptionRequest $request
         *
         * @return \Modules\Suscription\Http\Resources\UserRelSuscriptionPlansResource
         */
        public function store(PaymentsSuscriptionRequest $request)
        {
            $id = null;
            if ($request->has('id')) $id = (int)$request->id;
            $plan = UserRelSuscriptionPlan::firstOrNew(['id' => $id], []);
            $plan->fill($request->all());
            $plan->push();
            $salesNotes = UserRelSuscriptionPlan::setSaleNote($plan);

            if ( !empty($salesNotes)) {
                $plan->sale_notes = implode(',', $salesNotes);
                $plan->push();
            }
            return new UserRelSuscriptionPlansResource($plan);

        }


    }
