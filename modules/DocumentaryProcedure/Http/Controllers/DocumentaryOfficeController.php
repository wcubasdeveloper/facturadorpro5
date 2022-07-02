<?php

    namespace Modules\DocumentaryProcedure\Http\Controllers;

    use App\Models\Tenant\User;
    use Illuminate\Contracts\View\Factory;
    use Illuminate\Foundation\Application;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Illuminate\View\View;
    use Modules\DocumentaryProcedure\Http\Requests\OfficeRequest;
    use Modules\DocumentaryProcedure\Models\DocumentaryOffice as Stage;
    use Modules\DocumentaryProcedure\Models\RelUserToDocumentaryOffices as UserRelStages;
    use Throwable;


    /**
     * Class DocumentaryOfficeController
     *
     * @package Modules\DocumentaryProcedure\Http\Controllers
     */
    class DocumentaryOfficeController extends Controller
    {
        /**
         * @param Request $request
         *
         * @return Factory|Application|JsonResponse|View
         */
        public function index(Request $request)
        {
            $stages = Stage::orderBy('id');
            // $parents = $stages;
            if ($request->has('name')) {
                $stages->where('name', 'like', "%" . $request->name . "%");
            }

            $stages = $stages->get()->transform(function ($row) {
                /** @var Stage $row */
                return $row->getCollectionData();
            });

            if (request()->ajax()) {
                return response()->json(['data' => $stages], 200);
            }

            $users = User::GetWorkers()->get()->transform(function ($row) {
                return $row->getCollectionData();
            });


            return view('documentaryprocedure::offices', compact('stages', 'users'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param OfficeRequest $request
         *
         * @return JsonResponse
         */
        public function store(OfficeRequest $request)
        {
            $office = new Stage();

            $office
                ->setDescription($request->description)
                ->setName($request->name)
                // ->setParentId($request->parent_id)
                // ->setOrder($request->order)
            ;
            $office->fill($request->only('active'));
            $office->push();

            if ($request->has('users') && !empty($request->users)) {
                foreach ($request->users as $user) {
                    $e = UserRelStages::firstOrCreate([
                        'documentary_office_id' => $office->id,
                        'user_id' => $user,
                    ]);
                    $e->setActive(true)->push();
                }
            }
            return response()->json([
                'data' => $office,
                'message' => 'Oficina guardada de forma correcta.',
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
        public function update(OfficeRequest $request, $id)
        {


            $stage = Stage::findOrFail($id);

            $stage
                ->setDescription($request->description)
                ->setName($request->name)
                ->setDays($request->days)
            ;
            $stage->color = $request->color;
            $stage->fill($request->only('active'));


            $stage->push();
            if ($request->has('users') && !empty($request->users)) {

                $stages = UserRelStages::where('documentary_office_id', $stage->id)
                    ->wherenotin('user_id', $request->users)
                    ->delete();

                foreach ($request->users as $user) {
                    $e = UserRelStages::firstOrCreate([
                        'documentary_office_id' => $stage->id,
                        'user_id' => $user,
                    ]);
                    $e->setActive(true)->push();
                }
            }
            return response()->json([
                'data' => $stage->getCollectionData(),
                'message' => 'Oficina actualizada de forma correcta.',
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
                $office = Stage::findOrFail($id);
                $relations = UserRelStages::where('documentary_office_id', $office->id)->get();
                $office->delete();
                foreach ($relations as $rel) {
                    $rel->delete();
                }

                return response()->json([
                    'data' => null,
                    'message' => 'Oficina eliminada de forma correcta.',
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
