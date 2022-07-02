<?php

    namespace Modules\Suscription\Http\Controllers;

    use App\Http\Controllers\SearchCustomerController;
    use App\Http\Controllers\Tenant\PersonController;
    use App\Http\Requests\Tenant\PersonRequest;

    use Modules\Suscription\Http\Resources\PersonCollection;
    use App\Http\Resources\Tenant\PersonResource;
    use App\Models\System\Configuration;
    use App\Models\Tenant\Catalogs\Country;
    use App\Models\Tenant\Catalogs\Department;
    use App\Models\Tenant\Catalogs\District;
    use App\Models\Tenant\Catalogs\IdentityDocumentType;
    use App\Models\Tenant\Catalogs\Province;
    use App\Models\Tenant\Person;
    use App\Models\Tenant\PersonType;
    use Illuminate\Http\Request;

    class ClientSuscriptionController extends SuscriptionController
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
                // 'childrens' => 'Nombre de hijos',
            ];
        }

        public function Record(Request $request)
        {
            $personId = (int)($request->has('person')?$request->person:0);
            $records = SearchCustomerController::getCustomersToSuscriptionList($request,$personId);
            if($request->has('users') ){
                if($request->users == 'parent'){
                    $records->where('parent_id',0);
                }elseif($request->users == 'children'){
                    $records->where('parent_id','!=',0);
                }
            }
            $records = $records->firstOrFail();

            return ['data'=>$records->getCollectionData(true,true)];
        }

        public function Records(Request $request)
        {
            $records = SearchCustomerController::getCustomersToSuscriptionList($request);
            // getCustomersToSuscriptionList(Request $request = null, ?int $id = 0, $onlyParent = true){
            if($request->has('users') ){
                if($request->users == 'parent'){
                    $records->where('parent_id',0);
                }elseif($request->users == 'children'){
                    $records->where('parent_id','!=',0);
                }
            }
            // users
            return new PersonCollection($records->paginate(config('tenant.items_per_page')));
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
            // $configuration = Configuration::first();
            // $api_service_token = $configuration->token_apiruc === 'false' ? config('configuration.api_service_token') : $configuration->token_apiruc;
            $api_service_token = \App\Models\Tenant\Configuration::getApiServiceToken();

            return compact('countries', 'departments', 'provinces', 'districts', 'identity_document_types', 'locations', 'person_types', 'api_service_token');
        }


        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function index()
        {
            return view('suscription::clients.index');
        }
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function indexChildren()
        {
            return view('suscription::clients.index_child');
        }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function create()
        {
            return view('suscription::create');
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
            //
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
         * Almacena los datos de persona basado en el funcion amiento de su controlador
         *
         * @param PersonRequest $request
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function store(PersonRequest $request)
        {
            //
            $personController = new PersonController();

            $data  =  $personController->store($request);
            $childrens = $request->childrens;

            $demo = [];
            foreach($childrens as $child ){
                $child['parent_id']=$data['id'];
                $child['addresses']=$request->input('addresses');
                $childRequest = new PersonRequest();
                $childRequest->merge($child);
                $demo [] = $personController->store($childRequest);


            }
            $data[] = $demo;
            return $data;

        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param int     $id
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
         */
        public function update(Request $request, $id)
        {
            //
        }

    }
