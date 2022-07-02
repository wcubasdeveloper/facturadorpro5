<?php

namespace Modules\Inventory\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Inventory\Http\Resources\InventoryConfigurationResource;
use Modules\Inventory\Models\InventoryConfiguration;
use Modules\Inventory\Http\Requests\InventoryConfigurationRequest;
use App\Models\Tenant\Item;


class InventoryConfigurationController extends Controller
{

    public function index()
    {
        return view('inventory::config.index');
    }

    
    public function record() {

        $inventory_configuration = InventoryConfiguration::first();
        $record = new InventoryConfigurationResource($inventory_configuration);
        
        return $record;

    }

    public function store(InventoryConfigurationRequest $request) {

        $id = $request->input('id');
        $inventory_configuration = InventoryConfiguration::find($id);
        $inventory_configuration->fill($request->all());
        
        // migracion desarrollo sin terminar #1401
        if($request->generate_internal_id == true) {
            $item = Item::first();
            if($item) {
                $inventory_configuration->generate_internal_id = 0;
                return [
                    'success' => false,
                    'message' => 'Solo permitido si no tiene productos'
                ];
            }
        }
        
        $inventory_configuration->save();

        return [
            'success' => true,
            'message' => 'Configuración actualizada'
        ];

    }
    
}
