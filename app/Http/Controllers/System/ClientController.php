<?php

    namespace App\Http\Controllers\System;

    use App\CoreFacturalo\Helpers\Certificate\GenerateCertificate;
    use App\Http\Controllers\Controller;
    use App\Http\Requests\System\ClientRequest;
    use App\Http\Resources\System\ClientCollection;
    use App\Http\Resources\System\ClientResource;
    use App\Models\System\Client;
    use App\Models\System\Configuration;
    use App\Models\System\Module;
    use App\Models\System\Plan;
    use Carbon\Carbon;
    use Exception;
    use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
    use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
    use Hyn\Tenancy\Environment;
    use Hyn\Tenancy\Models\Hostname;
    use Hyn\Tenancy\Models\Website;
    use Illuminate\Http\Request;
    use Illuminate\Support\Collection;
    use Illuminate\Support\Facades\DB;
    use Modules\Document\Helpers\DocumentHelper;


    class ClientController extends Controller
    {
        public function index()
        {
            return view('system.clients.index');
        }

        public function create()
        {
            return view('system.clients.form');
        }

        public function tables()
        {

            $url_base = '.' . config('tenant.app_url_base');
            $plans = Plan::all();
            $types = [['type' => 'admin', 'description' => 'Administrador'], ['type' => 'integrator', 'description' => 'Listar Documentos']];
            $modules = Module::with('levels')
                ->where('sort', '<', 14)
                ->orderBy('sort')
                ->get()
                ->each(function ($module) {
                    return $this->prepareModules($module);
                });

            $apps = Module::with('levels')
                ->where('sort', '>', 13)
                ->orderBy('sort')
                ->get()
                ->each(function ($module) {
                    return $this->prepareModules($module);
                });

            // luego se podria crear grupos mediante algun modulo, de momento se pasan los id de manera directa
            $group_basic = Module::with('levels')
                ->whereIn('id', [7,1,6,17,18,5,14])
                ->orderBy('sort')
                ->get()
                ->each(function ($module) {
                    return $this->prepareModules($module);
                });
            $group_hotel = Module::with('levels')
                ->whereIn('id', [7,1,6,17,18,5,14,8,4])
                ->orderBy('sort')
                ->get()
                ->each(function ($module) {
                    return $this->prepareModules($module);
                });
            $group_pharmacy = Module::with('levels')
                ->whereIn('id', [7,1,6,17,18,5,14,8,4])
                ->orderBy('sort')
                ->get()
                ->each(function ($module) {
                    return $this->prepareModules($module);
                });
            $group_restaurant = Module::with('levels')
                ->whereIn('id', [7,1,6,17,18,5,14,8,4])
                ->orderBy('sort')
                ->get()
                ->each(function ($module) {
                    return $this->prepareModules($module);
                });
            $group_hotel_apps = Module::with('levels')
                ->whereIn('id', [15])
                ->orderBy('sort')
                ->get()
                ->each(function ($module) {
                    return $this->prepareModules($module);
                });
            $group_pharmacy_apps = Module::with('levels')
                ->whereIn('id', [19])
                ->orderBy('sort')
                ->get()
                ->each(function ($module) {
                    return $this->prepareModules($module);
                });
            $group_restaurant_apps = Module::with('levels')
                ->whereIn('id', [23])
                ->orderBy('sort')
                ->get()
                ->each(function ($module) {
                    return $this->prepareModules($module);
                });

            $config = Configuration::first();

            $certificate_admin = $config->certificate;
            $soap_username = $config->soap_username;
            $soap_password = $config->soap_password;

            return compact(
                'url_base',
                'plans',
                'types',
                'modules',
                'apps',
                'certificate_admin',
                'soap_username',
                'soap_password',
                'group_basic',
                'group_hotel',
                'group_pharmacy',
                'group_restaurant',
                'group_hotel_apps',
                'group_pharmacy_apps',
                'group_restaurant_apps');
        }

        private function prepareModules(Module $module): Module
        {
            $levels = [];
            foreach ($module->levels as $level) {
                array_push($levels, [
                    'id' => "{$module->id}-{$level->id}",
                    'description' => $level->description,
                    'module_id' => $level->module_id,
                    'is_parent' => false,
                ]);
            }
            unset($module->levels);
            $module->is_parent = true;
            $module->childrens = $levels;
            return $module;
        }

        public function records()
        {
            $records = Client::latest()
                ->get();
            foreach ($records as &$row) {
                $tenancy = app(Environment::class);
                $tenancy->tenant($row->hostname->website);
                // $row->count_doc = DB::connection('tenant')-> table('documents') ->count();
                $row->count_doc = DB::connection('tenant')
                    ->table('configurations')
                    ->first()
                    ->quantity_documents;
                $row->soap_type = DB::connection('tenant')
                    ->table('companies')
                    ->first()
                    ->soap_type_id;
                $row->count_user = DB::connection('tenant')
                    ->table('users')
                    ->count();
                $quantity_pending_documents = $this->getQuantityPendingDocuments();
                $row->document_regularize_shipping = $quantity_pending_documents['document_regularize_shipping'];
                $row->document_not_sent = $quantity_pending_documents['document_not_sent'];
                $row->document_to_be_canceled = $quantity_pending_documents['document_to_be_canceled'];

                if ($row->start_billing_cycle) {

                    $start_end_date = DocumentHelper::getStartEndDateForFilterDocument($row->start_billing_cycle);
                    $init = $start_end_date['start_date'];
                    $end = $start_end_date['end_date'];

                    // $row->init_cycle = $init;
                    // $row->end_cycle = $end;
                    // dd($start_end_date);

                    $row->count_doc_month = DB::connection('tenant')->table('documents')->whereBetween('date_of_issue', [$init, $end])->count();

                }

            }

            return new ClientCollection($records);
        }


        private function getQuantityPendingDocuments()
        {

            return [
                'document_regularize_shipping' => DB::connection('tenant')->table('documents')->where('state_type_id', '01')->where('regularize_shipping', true)->count(),
                'document_not_sent' => DB::connection('tenant')->table('documents')->whereIn('state_type_id', ['01', '03'])->where('date_of_issue', '<=', date('Y-m-d'))->count(),
                'document_to_be_canceled' => DB::connection('tenant')->table('documents')->where('state_type_id', '13')->count(),
            ];

        }


        public function record($id)
        {
            $client = Client::findOrFail($id);
            $tenancy = app(Environment::class);
            $tenancy->tenant($client->hostname->website);
            $user_id = 1;
            // Se buscan los valores en las tablas de los clientes, luego se compara con las tablas de admin para mostrar
            // correctamente la seleccion en la seccion de modulos de permisos
            $modules = DB::connection('tenant')
                ->table('modules')
                ->where('modules.order_menu', '<=', 13)
                ->join('module_user', 'module_user.module_id', '=', 'modules.id')
                ->where('module_user.user_id', $user_id)
                ->select('modules.value as value')
                ->get()
                ->pluck('value');
            $client->modules = DB::connection('system')
                ->table('modules')
                ->wherein('value', $modules)
                ->select('id')
                ->distinct()
                ->get()
                ->pluck('id');

            // Se buscan los valores en las tablas de los clientes, luego se compara con las tablas de admin para mostrar
            // correctamente la seleccion en la seccion de modulos de permisos
            // Apps
            $apps = DB::connection('tenant')
                ->table('modules')
                ->where('modules.order_menu', '>', 13)
                ->join('module_user', 'module_user.module_id', '=', 'modules.id')
                ->where('module_user.user_id', $user_id)
                ->select('modules.value as value')
                ->get()
                ->pluck('value');

            $client->apps = DB::connection('system')
                ->table('modules')
                ->wherein('value', $apps)
                ->select('id')
                ->distinct()
                ->get()
                ->pluck('id');

            // Se buscan los valores en las tablas de los clientes, luego se compara con las tablas de admin para mostrar
            // correctamente la seleccion en la seccion de modulos de permisos
            $levels = DB::connection('tenant')
                ->table('module_level_user')
                ->where('module_level_user.user_id', $user_id)
                ->join('module_levels', 'module_levels.id', '=', 'module_level_user.module_level_id')
                ->get()
                ->pluck('value');

            $client->levels = DB::connection('system')
                ->table('module_levels')
                ->wherein('value', $levels)
                ->select('id')
                ->distinct()
                ->get()
                ->pluck('id');

            $config = DB::connection('tenant')
                ->table('configurations')
                ->first();

            $client->config_system_env = $config->config_system_env;

            $company = DB::connection('tenant')
                ->table('companies')
                ->first();

            $client->soap_send_id = $company->soap_send_id;
            $client->soap_type_id = $company->soap_type_id;
            $client->soap_username = $company->soap_username;
            $client->soap_password = $company->soap_password;
            $client->soap_url = $company->soap_url;
            $client->certificate = $company->certificate;
            $client->number = $company->number;

            return new ClientResource($client);

        }

        public function charts()
        {
            $records = Client::all();
            $count_documents = [];
            foreach ($records as $row) {
                $tenancy = app(Environment::class);
                $tenancy->tenant($row->hostname->website);
                for ($i = 1; $i <= 12; $i++) {
                    $date_initial = Carbon::parse(date('Y') . '-' . $i . '-1');
                    $year_before = Carbon::now()->subYear()->format('Y');
                    $date_final = Carbon::parse(date('Y') . '-' . $i . '-' . cal_days_in_month(CAL_GREGORIAN, $i, $year_before));
                    $count_documents[] = [
                        'client' => $row->number,
                        'month' => $i,
                        'count' => $row->count_doc = DB::connection('tenant')
                            ->table('documents')
                            ->whereBetween('date_of_issue', [$date_initial, $date_final])
                            ->count()
                    ];
                }
            }

            $total_documents = collect($count_documents)->sum('count');

            $groups_by_month = collect($count_documents)->groupBy('month');
            $labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Set', 'Oct', 'Nov', 'Dic'];
            $documents_by_month = [];
            foreach ($groups_by_month as $month => $group) {
                $documents_by_month[] = $group->sum('count');
            }

            $line = [
                'labels' => $labels,
                'data' => $documents_by_month
            ];

            return compact('line', 'total_documents');
        }

        /**
         * @param Request $request
         *
         * @return array
         */
        public function update(Request $request)
        {
            /**
             * @var Collection $valueModules
             * @var Collection $valueLevels
             */
            $user_id = 1;
            $array_modules = [];
            $array_levels = [];


            $smtp_host = ($request->has('smtp_host')) ? $request->smtp_host : null;
            $smtp_password = ($request->has('smtp_password')) ? $request->smtp_password : null;
            $smtp_port = ($request->has('smtp_port')) ? $request->smtp_port : null;
            $smtp_user = ($request->has('smtp_user')) ? $request->smtp_user : null;
            $smtp_encryption = ($request->has('smtp_encryption')) ? $request->smtp_encryption : null;
            try {

                $temp_path = $request->input('temp_path');

                $name_certificate = $request->input('certificate');

                if ($temp_path) {

                    try {
                        $password = $request->input('password_certificate');
                        $pfx = file_get_contents($temp_path);
                        $pem = GenerateCertificate::typePEM($pfx, $password);
                        $name = 'certificate_' . $request->input('number') . '.pem';
                        if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'certificates'))) {
                            mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'certificates'));
                        }
                        file_put_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'certificates' . DIRECTORY_SEPARATOR . $name), $pem);
                        $name_certificate = $name;

                    } catch (Exception $e) {
                        return [
                            'success' => false,
                            'message' => $e->getMessage()
                        ];
                    }
                }


                $client = Client::findOrFail($request->id);

                $client
                    ->setSmtpHost($smtp_host)
                    ->setSmtpPort($smtp_port)
                    ->setSmtpUser($smtp_user)
                    //    ->setSmtpPassword($smtp_password)
                    ->setSmtpEncryption($smtp_encryption);
                if (!empty($smtp_password)) {
                    $client->setSmtpPassword($smtp_password);
                }
                $client->plan_id = $request->plan_id;
                $client->save();

                $plan = Plan::find($request->plan_id);

                $tenancy = app(Environment::class);
                $tenancy->tenant($client->hostname->website);
                $clientData = [
                    'plan' => json_encode($plan),
                    'config_system_env' => $request->config_system_env,
                    'limit_documents' => $plan->limit_documents,
                    'smtp_host' => $client->smtp_host,
                    'smtp_port' => $client->smtp_port,
                    'smtp_user' => $client->smtp_user,
                    'smtp_password' => $client->smtp_password,
                    'smtp_encryption' => $client->smtp_encryption,
                ];
                if (empty($client->smtp_password)) unset($clientData['smtp_password']);
                DB::connection('tenant')
                    ->table('configurations')
                    ->where('id', 1)
                    ->update($clientData);

                DB::connection('tenant')
                    ->table('companies')
                    ->where('id', 1)
                    ->update([
                        'soap_type_id' => $request->soap_type_id,
                        'soap_send_id' => $request->soap_send_id,
                        'soap_username' => $request->soap_username,
                        'soap_password' => $request->soap_password,
                        'soap_url' => $request->soap_url,
                        'certificate' => $name_certificate
                    ]);


                //modules
                DB::connection('tenant')
                    ->table('module_user')
                    ->where('user_id', $user_id)
                    ->delete();
                DB::connection('tenant')
                    ->table('module_level_user')
                    ->where('user_id', $user_id)
                    ->delete();

                // Obtenemos los value de las tablas
                $valueModules = DB::connection('system')
                    ->table('modules')
                    ->wherein('id', $request->modules)
                    ->get()
                    ->pluck('value');
                $valueLevels = DB::connection('system')
                    ->table('module_levels')
                    ->wherein('id', $request->levels)
                    ->get()
                    ->pluck('value');

                // Obtenemos el modelo del modulo, asi se obtendrá el id del elemento
                DB::connection('tenant')
                    ->table('modules')
                    ->wherein('value', $valueModules)
                    ->select(
                        'id as module_id',
                        DB::raw(" CONCAT($user_id) as user_id")
                    )
                    ->get()
                    ->transform(function ($module) use (&$array_modules) {
                        $array_modules[] = (array)$module;
                    });
                DB::connection('tenant')
                    ->table('module_levels')
                    ->wherein('value', $valueLevels)
                    ->select(
                        'id as module_level_id',
                        DB::raw(" CONCAT($user_id) as user_id")
                    )
                    ->get()
                    ->transform(function ($level) use (&$array_levels) {
                        $array_levels[] = (array)$level;
                    });

                // Se actualiza las tablas de permisos
                DB::connection('tenant')
                    ->table('module_user')
                    ->insert($array_modules);
                DB::connection('tenant')
                    ->table('module_level_user')
                    ->insert($array_levels);

                // Actualiza el modulo de farmacia.
                $config = (array)DB::connection('tenant')
                    ->table('configurations')
                    ->first();
                $config['is_pharmacy'] = (self::EnablePharmacy($user_id)) ? 1 : 0;
                DB::connection('tenant')
                    ->table('configurations')
                    ->update($config);
                return [
                    'success' => true,
                    'message' => 'Cliente Actualizado satisfactoriamente',
                    'modules' => $array_modules,
                    'levels' => $array_levels,
                ];

            } catch (Exception $e) {
                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];

            }

        }

        /**
         * Devuelve la informacion si el modulo de farmacia esta habilitado o no para activar la configuracion
         * correspondiente
         *
         * @param int $user_id
         *
         * @return bool
         */
        public static function EnablePharmacy($user_id = 0)
        {
            $modulo_id = DB::connection('tenant')
                ->table('modules')
                ->where('value', 'digemid')
                ->first()->id;
            $modulo = DB::connection('tenant')
                ->table('module_user')
                ->where('module_id', $modulo_id)
                ->where('user_id', $user_id)
                ->first();

            return ($modulo == null) ? false : true;

        }

        public function store(ClientRequest $request)
        {
            $temp_path = $request->input('temp_path');
            $configuration = Configuration::first();

            $name_certificate = $configuration->certificate;

            if ($temp_path) {

                try {
                    $password = $request->input('password_certificate');
                    $pfx = file_get_contents($temp_path);
                    $pem = GenerateCertificate::typePEM($pfx, $password);
                    $name = 'certificate_' . 'admin_tenant' . '.pem';
                    if (!file_exists(storage_path('app' . DIRECTORY_SEPARATOR . 'certificates'))) {
                        mkdir(storage_path('app' . DIRECTORY_SEPARATOR . 'certificates'));
                    }
                    file_put_contents(storage_path('app' . DIRECTORY_SEPARATOR . 'certificates' . DIRECTORY_SEPARATOR . $name), $pem);
                    $name_certificate = $name;

                } catch (Exception $e) {
                    return [
                        'success' => false,
                        'message' => $e->getMessage()
                    ];
                }
            }


            $subDom = strtolower($request->input('subdomain'));
            $uuid = config('tenant.prefix_database') . '_' . $subDom;
            $fqdn = $subDom . '.' . config('tenant.app_url_base');

            $website = new Website();
            $hostname = new Hostname();
            $this->validateWebsite($uuid, $website);

            DB::connection('system')->beginTransaction();
            try {
                $website->uuid = $uuid;
                app(WebsiteRepository::class)->create($website);
                $hostname->fqdn = $fqdn;
                app(HostnameRepository::class)->attach($hostname, $website);

                $tenancy = app(Environment::class);
                $tenancy->tenant($website);

                $token = str_random(50);

                $client = new Client();
                $client->hostname_id = $hostname->id;
                $client->token = $token;
                $client->email = strtolower($request->input('email'));
                $client->name = $request->input('name');
                $client->number = $request->input('number');
                $client->plan_id = $request->input('plan_id');
                $client->locked_emission = $request->input('locked_emission');
                $client->save();

                DB::connection('system')->commit();
            } catch (Exception $e) {
                DB::connection('system')->rollBack();
                app(HostnameRepository::class)->delete($hostname, true);
                app(WebsiteRepository::class)->delete($website, true);

                return [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
            }

            DB::connection('tenant')->table('companies')->insert([
                'identity_document_type_id' => '6',
                'number' => $request->input('number'),
                'name' => $request->input('name'),
                'trade_name' => $request->input('name'),
                'soap_type_id' => $request->soap_type_id,
                'soap_send_id' => $request->soap_send_id,
                'soap_username' => $request->soap_username,
                'soap_password' => $request->soap_password,
                'soap_url' => $request->soap_url,
                'certificate' => $name_certificate,
            ]);

            $plan = Plan::findOrFail($request->input('plan_id'));
            $http = config('tenant.force_https') == true ? 'https://' : 'http://';

            DB::connection('tenant')->table('configurations')->insert([
                'send_auto' => true,
                'locked_emission' => $request->input('locked_emission'),
                'locked_tenant' => false,
                'locked_users' => false,
                'limit_documents' => $plan->limit_documents,
                'limit_users' => $plan->limit_users,
                'plan' => json_encode($plan),
                'date_time_start' => date('Y-m-d H:i:s'),
                'quantity_documents' => 0,
                'config_system_env' => $request->config_system_env,
                'login' => json_encode([
                    'type' => 'image',
                    'image' => $http.$fqdn.'/images/fondo-5.svg',
                    'position_form' => 'right',
                    'show_logo_in_form' => false,
                    'position_logo' => 'top-left',
                    'show_socials' => false,
                    'facebook' => null,
                    'twitter' => null,
                    'instagram' => null,
                    'linkedin' => null,
                ]),
                'visual' => json_encode([
                    'bg' => 'white',
                    'header' => 'light',
                    'navbar' => 'fixed',
                    'sidebars' => 'light',
                    'sidebar_theme' => 'white'
                ]),
                'skin_id' => 2,
                'top_menu_a_id' => 1,
                'top_menu_b_id' => 15,
                'top_menu_c_id' => 76
            ]);


            $establishment_id = DB::connection('tenant')->table('establishments')->insertGetId([
                'description' => 'Oficina Principal',
                'country_id' => 'PE',
                'department_id' => '15',
                'province_id' => '1501',
                'district_id' => '150101',
                'address' => '-',
                'email' => $request->input('email'),
                'telephone' => '-',
                'code' => '0000'
            ]);

            DB::connection('tenant')->table('warehouses')->insertGetId([
                'establishment_id' => $establishment_id,
                'description' => 'Almacén Oficina Principal',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::connection('tenant')->table('series')->insert([
                ['establishment_id' => 1, 'document_type_id' => '01', 'number' => 'F001'],
                ['establishment_id' => 1, 'document_type_id' => '03', 'number' => 'B001'],
                ['establishment_id' => 1, 'document_type_id' => '07', 'number' => 'FC01'],
                ['establishment_id' => 1, 'document_type_id' => '07', 'number' => 'BC01'],
                ['establishment_id' => 1, 'document_type_id' => '08', 'number' => 'FD01'],
                ['establishment_id' => 1, 'document_type_id' => '08', 'number' => 'BD01'],
                ['establishment_id' => 1, 'document_type_id' => '20', 'number' => 'R001'],
                ['establishment_id' => 1, 'document_type_id' => '09', 'number' => 'T001'],
                ['establishment_id' => 1, 'document_type_id' => '40', 'number' => 'P001'],
                ['establishment_id' => 1, 'document_type_id' => '80', 'number' => 'NV01'],
                ['establishment_id' => 1, 'document_type_id' => '04', 'number' => 'L001'],
            ]);


            $user_id = DB::connection('tenant')->table('users')->insert([
                'name' => 'Administrador',
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'api_token' => $token,
                'establishment_id' => $establishment_id,
                'type' => $request->input('type'),
                'locked' => true,
                'permission_edit_cpe' => true,
            ]);


            if ($request->input('type') == 'admin') {
                $array_modules = [];
                $array_levels = [];
                foreach ($request->modules as $module) {
                    array_push($array_modules, [
                        'module_id' => $module, 'user_id' => $user_id
                    ]);
                }
                foreach ($request->levels as $level) {
                    array_push($array_levels, [
                        'module_level_id' => $level, 'user_id' => $user_id
                    ]);
                }
                DB::connection('tenant')->table('module_user')->insert($array_modules);
                DB::connection('tenant')->table('module_level_user')->insert($array_levels);
            } else {
                DB::connection('tenant')->table('module_user')->insert([
                    ['module_id' => 1, 'user_id' => $user_id],
                    ['module_id' => 3, 'user_id' => $user_id],
                    ['module_id' => 5, 'user_id' => $user_id],
                ]);
            }

            return [
                'success' => true,
                'message' => 'Cliente Registrado satisfactoriamente'
            ];
        }

        public function validateWebsite($uuid, $website)
        {

            $exists = $website::where('uuid', $uuid)->first();

            if ($exists) {
                throw new Exception("El subdominio ya se encuentra registrado");
            }

        }

        public function renewPlan(Request $request)
        {

            // dd($request->all());
            $client = Client::findOrFail($request->id);
            $tenancy = app(Environment::class);
            $tenancy->tenant($client->hostname->website);

            DB::connection('tenant')->table('billing_cycles')->insert([
                'date_time_start' => date('Y-m-d H:i:s'),
                'renew' => true,
                'quantity_documents' => DB::connection('tenant')->table('configurations')->where('id', 1)->first()->quantity_documents,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::connection('tenant')->table('configurations')->where('id', 1)->update(['quantity_documents' => 0]);


            return [
                'success' => true,
                'message' => 'Plan renovado con exito'
            ];

        }


        public function lockedUser(Request $request)
        {

            $client = Client::findOrFail($request->id);
            $client->locked_users = $request->locked_users;
            $client->save();

            $tenancy = app(Environment::class);
            $tenancy->tenant($client->hostname->website);
            DB::connection('tenant')->table('configurations')->where('id', 1)->update(['locked_users' => $client->locked_users]);

            return [
                'success' => true,
                'message' => ($client->locked_users) ? 'Limitar creación de usuarios activado' : 'Limitar creación de usuarios desactivado'
            ];

        }


        public function lockedEmission(Request $request)
        {

            $client = Client::findOrFail($request->id);
            $client->locked_emission = $request->locked_emission;
            $client->save();

            $tenancy = app(Environment::class);
            $tenancy->tenant($client->hostname->website);
            DB::connection('tenant')->table('configurations')->where('id', 1)->update(['locked_emission' => $client->locked_emission]);

            return [
                'success' => true,
                'message' => ($client->locked_emission) ? 'Limitar emisión de documentos activado' : 'Limitar emisión de documentos desactivado'
            ];

        }


        public function lockedTenant(Request $request)
        {

            $client = Client::findOrFail($request->id);
            $client->locked_tenant = $request->locked_tenant;
            $client->save();

            $tenancy = app(Environment::class);
            $tenancy->tenant($client->hostname->website);
            DB::connection('tenant')->table('configurations')->where('id', 1)->update(['locked_tenant' => $client->locked_tenant]);

            return [
                'success' => true,
                'message' => ($client->locked_tenant) ? 'Cuenta bloqueada' : 'Cuenta desbloqueada'
            ];

        }


        public function destroy($id)
        {
            $client = Client::find($id);

            if ($client->locked) {
                return [
                    'success' => false,
                    'message' => 'Cliente bloqueado, no puede eliminarlo'
                ];
            }

            $hostname = Hostname::find($client->hostname_id);
            $website = Website::find($hostname->website_id);

            app(HostnameRepository::class)->delete($hostname, true);
            app(WebsiteRepository::class)->delete($website, true);

            return [
                'success' => true,
                'message' => 'Cliente eliminado con éxito'
            ];
        }

        public function password($id)
        {
            $client = Client::find($id);
            $website = Website::find($client->hostname->website_id);
            $tenancy = app(Environment::class);
            $tenancy->tenant($website);
            DB::connection('tenant')->table('users')
                ->where('id', 1)
                ->update(['password' => bcrypt($client->number)]);

            return [
                'success' => true,
                'message' => 'Clave cambiada con éxito'
            ];
        }

        public function startBillingCycle(Request $request)
        {
            $client = Client::findOrFail($request->id);
            $client->start_billing_cycle = $request->start_billing_cycle;
            $client->save();

            return [
                'success' => true,
                'message' => ($client->start_billing_cycle) ? 'Ciclo de Facturacion definido.' : 'No se pudieron guardar los cambios.'
            ];
        }

        public function upload(Request $request)
        {
            if ($request->hasFile('file')) {
                $new_request = [
                    'file' => $request->file('file'),
                    'type' => $request->input('type'),
                ];

                return $this->upload_certificate($new_request);
            }
            return [
                'success' => false,
                'message' => 'Error al subir file.',
            ];
        }

        public function upload_certificate($request)
        {
            $file = $request['file'];
            $type = $request['type'];

            $temp = tempnam(sys_get_temp_dir(), $type);
            file_put_contents($temp, file_get_contents($file));

            $mime = mime_content_type($temp);
            $data = file_get_contents($temp);

            return [
                'success' => true,
                'data' => [
                    'filename' => $file->getClientOriginalName(),
                    'temp_path' => $temp,
                    //'temp_image' => 'data:' . $mime . ';base64,' . base64_encode($data)
                ]
            ];
        }


    }
