<?php

    namespace Modules\Inventory\Http\Controllers;

    use App\Http\Controllers\Controller;
    use App\Models\Tenant\CatItemSize;
    use Illuminate\Contracts\Pagination\LengthAwarePaginator;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Database\Eloquent\Collection;
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\View\View;


    /**
     *
     */
    class ItemSizeController extends Controller
    {

        /**
         * @return Factory|Application|View
         */
        public function index()
        {
            return view('inventory::extra_info.item_size.index');
        }

        /**
         * @return LengthAwarePaginator
         */
        public function records()
        {
            $records = CatItemSize::where('id', '!=', 0);
            return $records->paginate(config('tenant.items_per_page'));
        }

        /**
         * @param Request $request
         * @param int     $id
         *
         * @return CatItemSize|CatItemSize[]|Collection|Model|mixed|null
         */
        public function record(Request $request, $id = 0)
        {
            $record = CatItemSize::find($id);
            if (empty($record)) $record = new CatItemSize(['name' => '']);

            return $record;
        }

        /**
         * @param Request $request
         * @param int     $id
         *
         * @return CatItemSize|CatItemSize[]|Collection|Model|JsonResponse|mixed|null
         */
        public function store(Request $request, $id = 0)
        {
            $data = $request->all();

            $record = CatItemSize::find($id);
            $name = (isset($data['param']) && isset($data['param']['name'])) ? ucfirst(trim($data['param']['name'])) : null;
            $search = CatItemSize::where('name', '=', $name);
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
                $record = new CatItemSize();

            }
            $record->setName($name)->push();

            return $record;
        }

    }
