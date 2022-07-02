<?php

namespace Modules\Restaurant\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Restaurant\Models\RestaurantConfiguration;
use Modules\Restaurant\Models\RestaurantRole;
use App\Models\Tenant\User;


class RestaurantConfigurationController extends Controller
{
    /**
     * muestra vista para utilizar en mozo
     */
    public function configuration(){
        return view('restaurant::configuration.index');
    }

    /**
     * obtiene configuración para utilizar en mozo
     */
    public function record(){
        $configurations = RestaurantConfiguration::first();

        return [
            'success' => true,
            'data' => $configurations->getCollectionData()
        ];
    }

    /**
     * guarda cada nueva configuración para utilizar en mozo
     */
    public function setConfiguration(Request $request) {
        $configuration = RestaurantConfiguration::first();
        $configuration->fill($request->all());
        if(!$configuration->menu_pos && !$configuration->menu_order && !$configuration->menu_tables){
            $configuration->menu_pos = true;
        }
        $configuration->save();

        return [
            'success' => true,
            'configuration' => $configuration->getCollectionData(),
            'message' => 'Configuración actualizada',
        ];
    }

    /**
     * consulta los roles actuales
     */
    public function getRoles() {
        $roles = RestaurantRole::orderBy('name', 'ASC')->get();
        $alls = $roles->transform(function ($item) {
            return $item->getCollectionData();
        });

        return [
            'success' => true,
            'data' => $alls
        ];
    }

    /**
     * consulta los usuarios actuales
     */
    public function getUsers() {
        $users = User::orderBy('name')->get();
        $alls = $users->transform(function ($item) {
            return $item->getCollectionRestaurantData();
        });

        return [
            'success' => true,
            'data' => $alls
        ];
    }

    /**
     * asigna o actualiza un rol a un usuario
     */
    public function setRole(Request $request)
    {
        $user = User::find($request->user_id);
        $user->restaurant_role_id = $request->role_id;
        $user->save();

        return [
            'success' => true,
            'message' => 'Rol asignado a usuario exitosamente',
        ];
    }

}
