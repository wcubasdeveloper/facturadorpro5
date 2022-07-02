<?php

namespace Modules\Item\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Zone;
use Modules\Item\Http\Resources\ZoneCollection;
use Modules\Item\Http\Requests\ZoneRequest;

class ZoneController extends Controller
{

    public function index()
    {
        return view('item::zones.index');
    }


    public function columns()
    {
        return [
            'name' => 'Nombre',
        ];
    }

    public function records(Request $request)
    {
        $records = Zone::where($request->column, 'like', "%{$request->value}%");

        return new ZoneCollection($records->paginate(config('tenant.items_per_page')));
    }


    public function record($id)
    {
        $record = Zone::findOrFail($id);

        return $record;
    }

    /**
     * Crea o edita una nueva zona.
     * El nombre de zona debe ser único, por lo tanto se valida cuando el nombre existe.
     *
     * @param ZoneRequest $request
     *
     * @return array
     */
    public function store(ZoneRequest $request)
    {
        $id = (int)$request->input('id');
        $name = $request->input('name');
        $error = null;
        $Zone = null;
        if (!empty($name)) {
            $Zone = Zone::where('name', $name);
            if (empty($id)) {
                $Zone = $Zone->first();
                if (!empty($Zone)) {
                    $error = 'El nombre de zona ya existe';
                }
            } else {
                $Zone = $Zone->where('id', '!=', $id)->first();
                if (!empty($Zone)) {
                    $error = 'El nombre de zona ya existe para otro registro';
                }
            }
        }
        $data = [
            'success' => false,
            'message' => $error,
            'data' => $Zone
        ];
        if (empty($error)) {
            $Zone = Zone::firstOrNew(['id' => $id]);
            $Zone->fill($request->all());
            $Zone->save();
            $data = [
                'success' => true,
                'message' => ($id) ? 'Zona editada con éxito' : 'Zona registrada con éxito',
                'data' => $Zone
            ];
        }
        return $data;

    }

    public function destroy($id)
    {
        try {

            $Zone = Zone::findOrFail($id);
            $Zone->delete();

            return [
                'success' => true,
                'message' => 'Zona eliminada con éxito'
            ];

        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false,'message' => "La Zona esta siendo usada por otros registros, no puede eliminar"] : ['success' => false,'message' => "Error inesperado, no se pudo eliminar la Zona"];

        }

    }




}
