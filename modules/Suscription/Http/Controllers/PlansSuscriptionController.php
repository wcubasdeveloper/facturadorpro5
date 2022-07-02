<?php

    namespace Modules\Suscription\Http\Controllers;

    use Illuminate\Http\Request;
    use Modules\Suscription\Http\Requests\PlanSuscriptionRequest;
    use Modules\Suscription\Http\Resources\SuscriptionPlansCollection;
    use Modules\Suscription\Http\Resources\SuscriptionPlansResource;
    use Modules\Suscription\Models\Tenant\CatPeriod;
    use Modules\Suscription\Models\Tenant\SuscriptionPlan;

    class PlansSuscriptionController extends SuscriptionController
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

            return new SuscriptionPlansResource(SuscriptionPlan::findOrFail($request->person));
        }

        public function Records(Request $request)
        {
            $records = SuscriptionPlan::query();
            if ($request->has('column') && !empty($request->column)) {
                $records->where($request->column, 'like', "%{$request->value}%");
            }
            /** @var \Illuminate\Database\Query\Builder $records */
            $records->orderBy('name');
            // ->where('type', $type)
            return new SuscriptionPlansCollection($records->paginate(config('tenant.items_per_page')));
        }

        public function Tables()
        {

            $periods = CatPeriod::where('active', 1)->get();

            return compact(
                'periods'
            );

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
            return view('suscription::plans.index');
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

        /**
         * Update the specified resource in storage.
         *
         * @param PlanSuscriptionRequest $request
         * @param int                    $id
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function update(PlanSuscriptionRequest $request, $id)
        {
            //
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param PlanSuscriptionRequest $request
         *
         * @return \Modules\Suscription\Http\Resources\SuscriptionPlansResource
         */
        public function store(PlanSuscriptionRequest $request)
        {
            $id = null;
            $requestItems = $request->items;
            if ($request->has('id')) $id = (int)$request->id;
            $period = CatPeriod::where('period', 'Y')->first();
            if ($request->has('periods')) {
                if (CatPeriod::where('period', $request->periods)->first() != null) {
                    $period = CatPeriod::where('period', $request->periods)->first();
                }
            }
            $plan = SuscriptionPlan::firstOrNew(['id' => $id], $request->all());

            $plan->fill($request->all());
            $plan->setName($request->name)
                ->setDescription($request->description)
                ->setCatPeriod($period)
                ->push();
            foreach ($plan->items as $i) {
                $i->delete();
            }
            // Elimina todos los items anteriores
            // Inserta todos los nuevos items
            foreach ($requestItems as $item) {
                $plan->items()->create($item);

            }


            return new SuscriptionPlansResource($plan);

        }

        public function destroy($id)
        {

            $record = SuscriptionPlan::findOrFail($id);
            $record->delete();

            return [
                'success' => true,
                'message' => 'Plan eliminado con éxito'
            ];

        }

    }
