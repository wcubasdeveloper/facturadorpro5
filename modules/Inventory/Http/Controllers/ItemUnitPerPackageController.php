<?php

    namespace Modules\Inventory\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\Tenant\Catalogs\CatItemUnitsPerPackage;
    use Illuminate\Http\Request;


    class ItemUnitPerPackageController extends Controller
    {

        public function index()
        {
            return view('inventory::extra_info.item_unit_per_package.index');
        }

        public function records()
        {


            $records = CatItemUnitsPerPackage::where('id', '!=', 0);
            return $records->paginate(config('tenant.items_per_page'));
        }

        public function record(Request $request, $id = 0)
        {
            $record = CatItemUnitsPerPackage::find($id);
            if (empty($record)) $record = new CatItemUnitsPerPackage(['name' => '']);

            return $record;
        }

        public function store(Request $request, $id = 0)
        {
            $data = $request->all();

            $record = CatItemUnitsPerPackage::find($id);
            $name = (isset($data['param']) && isset($data['param']['name'])) ? ucfirst(trim($data['param']['name'])) : null;
            $search = CatItemUnitsPerPackage::where('name', '=', $name);
            if (!empty($record)) {
                $search->where('id', '!=', $id);
            }

            $fund = $search->first();
            if (!empty($fund)) {
                return response()->json([
                    'success' => false,
                    'message' => "El nombre ya se encuetra ($name). intente otro",
                ], "504");
            }
            if (empty($name)) {
                return response()->json([
                    'success' => false,
                    'message' => "El nombre no debe estar vacio",
                ], "504");
            }
            if (empty($record)) {
                $record = new CatItemUnitsPerPackage();

            }
            $record->setName($name)->push();

            return $record;
        }
    }
