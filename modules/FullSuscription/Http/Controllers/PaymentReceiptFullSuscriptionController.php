<?php

    namespace Modules\FullSuscription\Http\Controllers;

    use App\Models\Tenant\Catalogs\AffectationIgvType;
    use App\Models\Tenant\Catalogs\Country;
    use App\Models\Tenant\Catalogs\CurrencyType;
    use App\Models\Tenant\Catalogs\Department;
    use App\Models\Tenant\Catalogs\District;
    use App\Models\Tenant\Catalogs\IdentityDocumentType;
    use App\Models\Tenant\Catalogs\Province;
    use App\Models\Tenant\Catalogs\UnitType;
    use App\Models\Tenant\Company;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\Item;
    use App\Models\Tenant\PaymentMethodType;
    use App\Models\Tenant\PersonType;
    use Carbon\Carbon;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\Request;
    use Illuminate\Http\Response;
    use Illuminate\View\View;
    use Modules\FullSuscription\Http\Resources\SuscriptionPlansCollection;
    use Modules\FullSuscription\Models\Tenant\SuscriptionPlan;

    class PaymentReceiptFullSuscriptionController extends FullSuscriptionController
    {
        public function Tables()
        {

            $countries = Country::whereActive()->orderByDescription()->get();
            $departments = Department::whereActive()->orderByDescription()->get();
            $provinces = Province::whereActive()->orderByDescription()->get();
            $districts = District::whereActive()->orderByDescription()->get();
            $identity_document_types = IdentityDocumentType::whereActive()->get();
            $person_types = PersonType::get();
            $locations = $this->getLocationCascade();
            // $configuration = Configuration::first();
            // $api_service_token = $configuration->token_apiruc == 'false' ? config('configuration.api_service_token') : $configuration->token_apiruc;
            $api_service_token = Configuration::getApiServiceToken();

            $unit_types = UnitType::whereActive()->orderByDescription()->get();
            $currency_types = CurrencyType::whereActive()->orderByDescription()->get();
            $affectation_igv_types = AffectationIgvType::whereActive()->get();

            $payments_credit = PaymentMethodType::select('id')->NonCredit()->get()->toArray();
            $payments_credit = PaymentMethodType:: getPaymentMethodTypes($payments_credit);
            $startDate = Carbon::createFromFormat('Y-m-d', '2022-01-01')->format('Y-m-d');

            return compact('unit_types',
                'currency_types',
                'affectation_igv_types',
                'startDate',
                'countries',
                'departments',
                'provinces',
                'districts',
                'identity_document_types',
                'locations',
                'person_types',
                'payments_credit',
                'api_service_token');
        }

        /**
         * Devuelve un array para Privincia, distrito
         *
         * @return array
         */
        public function getLocationCascade()
        {
            $locations = [];
            $departments = Department::where('active', true)->get();
            foreach ($departments as $department) {
                $children_provinces = [];
                foreach ($department->provinces as $province) {
                    $children_districts = [];
                    foreach ($province->districts as $district) {
                        $children_districts[] = [
                            'value' => $district->id,
                            'label' => $district->id . " - " . $district->description
                        ];
                    }
                    $children_provinces[] = [
                        'value' => $province->id,
                        'label' => $province->description,
                        'children' => $children_districts
                    ];
                }
                $locations[] = [
                    'value' => $department->id,
                    'label' => $department->description,
                    'children' => $children_provinces
                ];
            }

            return $locations;
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return Factory|Application|Response|View
         */
        public function create()
        {
            return view('full_suscription::create');
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         *
         * @return Factory|Application|Response|View
         */
        public function destroy($id)
        {

            $record = SuscriptionPlan::findOrFail($id);
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
         * @return Factory|Application|Response|View
         */
        public function edit($id)
        {
            return view('full_suscription::edit');
        }

        /**
         * @param Request $request
         *
         * @return Builder
         */
        public function getServiceRecords(Request $request)
        {

            $records = Item::whereTypeUser()->whereNotIsSet()->whereService();
            switch ($request->column) {
                case 'brand':
                    $records->whereHas('brand', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->value}%");
                    });
                    break;
                case 'active':
                    $records->whereIsActive();
                    break;

                case 'inactive':
                    $records->whereIsNotActive();
                    break;

                default:
                    if ($request->has('column')) {
                        $filter = 'id';
                        if ($request->column != 'index') $filter = $request->column;
                        $records->where($filter, 'like', "%{$request->value}%");
                    }
                    break;
            }
            $filter = 'description';

            if ($request->has('column') && $request->column != 'index') {
                $filter = $request->column;

            }
            return $records->orderBy($filter);

        }

        /**
         * Display a listing of the resource.
         *
         * @return Factory|Application|Response|View
         */
        public function index()
        {
            $company = Company::select('soap_type_id')->first();
            $soap_company = $company->soap_type_id;

            return view('full_suscription::payment_receipt.index', compact('soap_company'));
        }

        /**
         * Display a listing of the resource.
         *
         * @return Factory|Application|Response|View
         */
        public function payments_index()
        {
            return view('subscription::payments.index');
        }

        public function plansColumns()
        {
            return [
                'cat_period_id' => 'Periodos',
                'name' => 'Descripción',
                'description' => 'Nombre',
            ];

        }

        public function plansRecord(Request $request)
        {

            $record = new SuscriptionPlansCollection(SuscriptionPlan::findOrFail($request->person));
            return $record;
        }

        public function plansRecords(Request $request)
        {
            $type = 'customers';
            $records = SuscriptionPlan::where($request->column, 'like', "%{$request->value}%")
                // ->where('type', $type)
                ->orderBy('name');

            return new SuscriptionPlansCollection($records->paginate(config('tenant.items_per_page')));
        }

        /**
         * Display a listing of the resource.
         *
         * @return Factory|Application|Response|View
         */
        public function plans_index()
        {
            return view('full_suscription::plans.index');
        }

        /**
         * Show the specified resource.
         *
         * @param int $id
         *
         * @return Factory|Application|Response|View
         */
        public function show($id)
        {
            return view('full_suscription::show');
        }


    }
