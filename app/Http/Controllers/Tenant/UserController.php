<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\UserRequest;
use App\Http\Resources\Tenant\UserCollection;
use App\Http\Resources\Tenant\UserResource;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Module;
use App\Models\Tenant\Series;
use App\Models\Tenant\User;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Zone;

class UserController extends Controller
{
    public function index()
    {
        return view('tenant.users.index');
    }

    public function record($id)
    {
        $record = new UserResource(User::findOrFail($id));

        return $record;
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

    public function tables() {
        /** @var User $user */
        $user = User::find(1);
        $modulesTenant = $user->getCurrentModuleByTenant()
                              ->pluck('module_id')
                              ->all();

        $levelsTenant = $user->getCurrentModuleLevelByTenant()
                             ->pluck('module_level_id')
                             ->toArray();


        $modules = Module::with(['levels' => function ($query) use ($levelsTenant) {
            $query->whereIn('id', $levelsTenant);
        }])
                         ->orderBy('order_menu')
                         ->whereIn('id', $modulesTenant)
                         ->get()
                         ->each(function ($module) {
                             return $this->prepareModules($module);
                         });
        $establishments = Establishment::orderBy('description')->get();
        $documents = DocumentType::OnlyAvaibleDocuments()->get();
        $series = Series::FilterEstablishment()->FilterDocumentType()->get();
        $types = [
            ['type' => 'admin', 'description' => 'Administrador'],
            ['type' => 'seller', 'description' => 'Vendedor'],
        ];

        $config_permission_to_edit_cpe = Configuration::select('permission_to_edit_cpe')->first()->permission_to_edit_cpe;
        $zones = Zone::all();

        return compact('modules', 'establishments', 'types', 'documents', 'series', 'config_permission_to_edit_cpe','zones');
    }

    public function regenerateToken(User $user){
        $data = [
            'api_token'=>$user->api_token,
            'success'=>false,
            'message' => 'No puedes cambiar el token'
        ];
        if(auth()->user()->isAdmin()){
            $user->updateToken()->push();
            $data['api_token']=$user->api_token;
            $data['success']=true;
            $data['message']='Token cambiado';

        }
        return $data;
    }
    public function store(UserRequest $request) {
        $id = $request->input('id');

        if (!$id) { //VALIDAR EMAIL DISPONIBLE
            $verify = User::where('email', $request->input('email'))->first();
            if ($verify) {
                return [
                    'success' => false,
                    'message' => 'Email no disponible. Ingrese otro Email'
                ];
            }
        }
        /** @var User $user */
        $user = User::firstOrNew(['id' => $id]);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->establishment_id = $request->input('establishment_id');
        $user->type = $request->input('type');
        // Zona por usuario
        // $user->zone_id = $request->input('zone_id');

        if (!$id) {
            $user->api_token = str_random(50);
            $user->password = bcrypt($request->input('password'));
        } elseif ($request->has('password')) {
            if (config('tenant.password_change')) {
                $user->password = bcrypt($request->input('password'));
            }
        }
        $user->setDocumentId($request->input('document_id'))
             ->setSeriesId($request->input('series_id'));
        $user->establishment_id = $request->input('establishment_id');

        $user->recreate_documents = $request->input('recreate_documents');
        $user->permission_edit_cpe = $request->input('permission_edit_cpe');
        $user->create_payment = $request->input('create_payment');
        $user->delete_payment = $request->input('delete_payment');

        $user->save();

        if ($user->id != 1) {
            $user->setModuleAndLevelModule($request->modules,$request->levels);
            /*
            $array_modules = [];
            $array_levels = [];
            DB::connection('tenant')->table('module_user')->where('user_id', $user->id)->delete();
            DB::connection('tenant')->table('module_level_user')->where('user_id', $user->id)->delete();
            foreach ($request->modules as $module) {
                array_push($array_modules, [
                    'module_id' => $module, 'user_id' => $user->id
                ]);
            }
            foreach ($request->levels as $level) {
                array_push($array_levels, [
                    'module_level_id' => $level, 'user_id' => $user->id
                ]);
            }
            DB::connection('tenant')->table('module_user')->insert($array_modules);
            DB::connection('tenant')->table('module_level_user')->insert($array_levels);
            */
        }

        return [
            'success' => true,
            'message' => ($id) ? 'Usuario actualizado' : 'Usuario registrado'
        ];
    }

    public function records()
    {
        $records = User::all();

        return new UserCollection($records);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return [
            'success' => true,
            'message' => 'Usuario eliminado con éxito'
        ];
    }
}
