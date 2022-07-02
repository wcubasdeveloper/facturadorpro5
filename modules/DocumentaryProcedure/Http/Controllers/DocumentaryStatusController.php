<?php

    namespace Modules\DocumentaryProcedure\Http\Controllers;

    use App\Models\Tenant\User;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Illuminate\View\View;
    use Modules\DocumentaryProcedure\Http\Requests\StatusRequest;
    use Modules\DocumentaryProcedure\Models\DocumentaryGuidesNumberStatus;
    use Throwable;


    /**
     * Class DocumentaryOfficeController
     *
     * @package Modules\DocumentaryProcedure\Http\Controllers
     */
    class DocumentaryStatusController extends Controller
    {
        /**
         * @param Request $request
         *
         * @return Factory|Application|JsonResponse|View
         */
        public function index(Request $request)
        {
            $status = DocumentaryGuidesNumberStatus::orderBy('id');

            if ($request->has('name')) {
                $status->where('name', 'like', "%" . $request->name . "%");
            }

            $status = $status->get()->transform(function ($row) {
                /** @var DocumentaryGuidesNumberStatus $row */
                return $row->getCollectionData();
            });

            if (request()->ajax()) {
                return response()->json(['data' => $status], 200);
            }

            $users = User::GetWorkers()->get()->transform(function ($row) {
                return $row->getCollectionData();
            });


            return view('documentaryprocedure::status', compact('status', 'users'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param StatusRequest $request
         *
         * @return JsonResponse
         */
        public function store(StatusRequest $request)
        {
            $name =$request->name;
            $find = [
                'name' => $name,
                'color' => $request->color,
            ];
            $id = $request->has('id')?$request->id:0;
            if($id < 1 ){
                $office = DocumentaryGuidesNumberStatus::where('name',$name)->first();;
                if(!empty($office)){
                    return response()->json([
                        'data' => $office,
                        'message' => 'El nombre ya se encuentra registrado.',
                        'succes' => false,
                    ], 500);
                }
                $office = new DocumentaryGuidesNumberStatus($find);
                $office->push();
            }else{
                $office = DocumentaryGuidesNumberStatus::find($id);
                $office->fill($find);
                $office->push();
            }


            return response()->json([
                'data' => $office,
                'message' => 'Estado guardada de forma correcta.',
                'succes' => true,
            ], 200);
        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param int     $id
         *
         * @return JsonResponse
         */
        public function update(StatusRequest $request, $id)
        {


            $stage = DocumentaryGuidesNumberStatus::findOrFail($id);

            $stage->name = $request->name;
            $stage->color = $request->color;


            $stage->push();


            return response()->json([
                'data' => $stage->toArray(),
                'message' => 'Estado actualizada de forma correcta.',
                'succes' => true,
            ], 200);
        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         *
         * @return JsonResponse
         */
        public function destroy($id)
        {
            try {
                $office = DocumentaryGuidesNumberStatus::findOrFail($id);
                $office->delete();

                return response()->json([
                    'data' => null,
                    'message' => 'Estado eliminada de forma correcta.',
                    'succes' => true,
                ], 200);
            } catch (Throwable $th) {
                return response()->json([
                    'success' => false,
                    'data' => 'Ocurrió un error al procesar su petición. Detalles: ' . $th->getMessage(),
                ], 500);
            }
        }
    }
