<?php

    namespace Modules\DocumentaryProcedure\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Modules\DocumentaryProcedure\Models\DocumentaryFilesRequirement as Requirements;


    /**
     * Class DocumentaryRequirementsController
     *
     * @package Modules\DocumentaryProcedure\Http\Controllers
     */
    class DocumentaryRequirementsController extends Controller {


        /**
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
         */
        public function index() {
            $requirements_list = Requirements::orderby('id')->get()->transform(function ($row) {
                return $row->getCollectionData();
            });
            return view('documentaryprocedure::requirements', compact('requirements_list'));
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function store(Request $request)
        {

            $requirement = new Requirements([
                                                'name' => $request->name,
                                                'file' => (bool)$request->file,
                                            ]);
            $requirement->push();
            return response()->json([
                                        'data'    => $requirement->getCollectionData(),
                                        'message' => "El requerimiento se ha guardado exitsamente",
                                        'succes'  => true,
                                    ], 200);

        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function update(Request $request) {
            $requirement = Requirements::find($request->id);
            $data = [];
            if(!empty($requirement)){
                $requirement
                    ->setName($request->name)
                    ->setFile($request->file);
                $requirement->push();
                $data = $requirement->getCollectionData();
            }

            return response()->json([
                                        'data'    => $data,
                                        'message' => 'El requerimiento se ha actualizado exitsamente',
                                        'succes'  => true,
                                    ], 200);

        }

        /**
         * Remove the specified resource from storage.
         *
         * @param int $id
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function destroy($id) {
            $requirement = Requirements::find($id);
            if(!empty($requirement)){
                $requirement->delete();
            }


            return response()->json([
                                        'message' => 'El requerimiento se ha eliminado',
                                        'succes'  => true,
                                    ], 200);
        }
    }
