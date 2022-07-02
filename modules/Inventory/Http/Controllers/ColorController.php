<?php

    namespace Modules\Inventory\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\Tenant\Catalogs\CatColorsItem;
    use Illuminate\Http\Request;


    class ColorController extends Controller
    {

        public function index()
        {
            return view('inventory::colors.index');
        }


        public function create()
        {
            return view('inventory::colors.form');
        }

        public function records()
        {

            $records = CatColorsItem::where('id', '!=', 0);
            return $records->paginate(config('tenant.items_per_page'));
        }

        public function record(Request $request, $id = 0)
        {
            $record = CatColorsItem::find($id);
            if (empty($record)) $record = new CatColorsItem(['name' => '']);

            return $record;
        }

        public function store(Request $request, $id = 0)
        {
            $data = $request->all();

            $record = CatColorsItem::find($id);
            $name = (isset($data['param']) && isset($data['param']['name'])) ? ucfirst(trim($data['param']['name'])) : null;
            $search = CatColorsItem::where('name', '=', $name);
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
                $record = new CatColorsItem();

            }
            $record->setName($name)->push();

            return $record;
        }

    }
