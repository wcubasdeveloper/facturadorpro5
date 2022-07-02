<?php

    namespace Modules\FullSuscription\Http\Controllers;

    use App\Http\Controllers\SearchCustomerController;
    use App\Http\Controllers\Tenant\PersonController;
    use App\Http\Requests\Tenant\PersonRequest;
    use App\Models\Tenant\Catalogs\Country;
    use App\Models\Tenant\Catalogs\Department;
    use App\Models\Tenant\Catalogs\District;
    use App\Models\Tenant\Catalogs\IdentityDocumentType;
    use App\Models\Tenant\Catalogs\Province;
    use App\Models\Tenant\Configuration;
    use App\Models\Tenant\PersonType;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\Request;
    use Illuminate\View\View;
    use Modules\FullSuscription\Http\Resources\SuscriptionPersonCollection;
    use Modules\FullSuscription\Models\Tenant\FullSuscriptionServerDatum;
    use Modules\FullSuscription\Models\Tenant\FullSuscriptionUserDatum;

    class ClientFullSuscriptionController extends FullSuscriptionController
    {
        /**
         * @return string[]
         */
        public function Columns()
        {
            return [
                'name' => 'Nombre',
                'number' => 'Número',
                'document_type' => 'Tipo de documento',
                'discord_channel' => 'Canal',
                'telephone' => 'Teléfono',
            ];
        }

        public function Record(Request $request)
        {
            $personId = (int)($request->has('person') ? $request->person : 0);
            $records = SearchCustomerController::getCustomersToSuscriptionList($request, $personId)->firstOrFail();

            return ['data' => $records->getCollectionData(true, false, true)];
        }

        public function RecordServer(Request $request)
        {
            $personId = (int)($request->has('person') ? $request->person : 0);
            $records = FullSuscriptionServerDatum::find($personId);

            return ['data' => $records];
        }

        public function Records(Request $request)
        {
            $records = SearchCustomerController::getCustomersToSuscriptionList($request);
            return new SuscriptionPersonCollection($records->paginate(config('tenant.items_per_page')));
        }

        public function Tables()
        {

            $countries = Country::whereActive()->orderByDescription()->get();
            $departments = Department::whereActive()->orderByDescription()->get();
            $provinces = Province::whereActive()->orderByDescription()->get();
            $districts = District::whereActive()->orderByDescription()->get();
            $identity_document_types = IdentityDocumentType::whereActive()->get();
            $person_types = PersonType::get();
            $locations = $this->getLocationCascade();
             $configuration = Configuration::first();
            $configuration = $configuration->getCollectionData();
            // $api_service_token = $configuration->token_apiruc === 'false' ? config('configuration.api_service_token') : $configuration->token_apiruc;
            $api_service_token = Configuration::getApiServiceToken();

            return compact('countries',
                'departments',
                'provinces',
                'districts',
                'configuration',
                'identity_document_types',
                'locations',
                'person_types',
                'api_service_token'
            );
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return Application|Factory|View
         */
        public function create()
        {
            return view('full_suscription::create');
        }

        /**
         * Show the form for editing the specified resource.
         *
         * @param int $id
         *
         * @return Application|Factory|View
         */
        public function edit($id)
        {
            return view('full_suscription::edit');
        }

        /**
         * Display a listing of the resource.
         *
         * @return Application|Factory|View
         */
        public function index()
        {
            return view('full_suscription::clients.index');
        }

        /**
         * Show the specified resource.
         *
         * @param int $id
         *
         * @return Application|Factory|View
         */
        public function show($id)
        {
            return view('full_suscription::show');
        }

        /**
         * Display a listing of the resource.
         *
         * @return Application|Factory|View
         */
        public function indexChildren()
        {
            return view('full_suscription::clients.index_child');
        }

        /**
         * Almacena los datos de persona basado en el funcion amiento de su controlador
         *
         * @param PersonRequest $request
         *
         * @return array
         */
        public function store(PersonRequest $request)
        {
            //

            $personController = new PersonController();

            $data = $personController->store($request);
            $servers = ($request->has('servers'))?$request->servers:null;
            $demo = [];
            $person_id = $data['id'];
            if(!empty($servers)) {
                foreach ($servers as $server) {
                    $server_data = (isset($server['id'])) ?
                        FullSuscriptionServerDatum::find($server['id']) :
                        new FullSuscriptionServerDatum($server);
                    $server_data
                        ->setPersonId($person_id)
                        ->setHost($server['host'] ?? null)
                        ->setIp($server['ip'] ?? null)
                        ->setUser($server['user'] ?? null)
                        ->setPassword($server['password'] ?? null)
                        ->push();

                }
            }
            $extra_fields = [];

            $extra_data_req = $request->all();
            $extra_fields['discord_user'] = $extra_data_req['discord_user'] ?? null;
            $extra_fields['slack_channel'] = $extra_data_req['slack_channel'] ?? null;
            $extra_fields['discord_channel'] = $extra_data_req['discord_channel'] ?? null;
            $extra_fields['gitlab_user'] = $extra_data_req['gitlab_user'] ?? null;
            $extra_data = FullSuscriptionUserDatum::where('person_id', $person_id)->first();
            if (empty($extra_data)) {
                $extra_data = new FullSuscriptionUserDatum($extra_fields);
            }
            $extra_data
                ->setPersonId($person_id)
                ->setDiscordUser($extra_fields['discord_user'])
                ->setSlackChannel($extra_fields['slack_channel'])
                ->setDiscordChannel($extra_fields['discord_channel'])
                ->setGitlabUser($extra_fields['gitlab_user'])
                ->push();

            $data[] = $demo;
            return $data;

        }


    }
