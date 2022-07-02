<?php

    namespace Modules\DocumentaryProcedure\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Routing\Controller;
    use Modules\DocumentaryProcedure\Http\Requests\ProcessRequest;
    use Modules\DocumentaryProcedure\Models\DocumentaryFilesRequirement as Requirements;
    use Modules\DocumentaryProcedure\Models\DocumentaryOffice as Stages;
    use Modules\DocumentaryProcedure\Models\DocumentaryProcess;
    use Modules\DocumentaryProcedure\Models\DocumentaryProcess as Process;
    use Modules\DocumentaryProcedure\Models\DocumentaryProcessesRelReq as RequirementsRel;
    use Throwable;

    /**
     * Class DocumentaryProcessController
     *
     * @package Modules\DocumentaryProcedure\Http\Controllers
     */
    class DocumentaryProcessController extends Controller {

        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\View\View
         */
        public function index(Request $request) {
            $processes = $this->getRecords($request);

            if (request()->ajax()) {
                $processes = $processes->get()->transform(function (DocumentaryProcess $row) {
                    return $row->getCollectionData();
                });

                return response()->json(['data' => $processes], 200);
            }
            $processes = $processes->get()->transform(function (Process $row) {
                return $row->getCollectionData();
            });

            $stages = Stages::where('active', 1)->get()->transform(function ($row) {
                /** @var Stages $row */
                return $row->getCollectionData();
            });
            $requirements = Requirements::orderby('id')->get()->transform(function ($row) {
                return $row->getCollectionData();
            });

            // dd($processes);
            return view('documentaryprocedure::process', compact('processes',
                                                                 'stages',
                                                                 'requirements'));
        }

        /**
         * @param \Illuminate\Http\Request|null $request
         *
         * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Modules\DocumentaryProcedure\Models\DocumentaryProcess
         */
        public function getRecords(Request $request = null) {
            $processes = Process::orderBy('id');
            if ($request != null && $request->has('name') && !empty($request->name)) {
                $processes->where('name', 'like', "%".$request->name."%");
            }

            return $processes;
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param Request $request
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function store(ProcessRequest $request) {
            $process = new Process(['active' => $request->active]);
            // dd($request->all());
            $process
                ->setName($request->name)
                ->setDescription($request->description)
                ->setPrice($request->price)
                ->setDocumentaryOffices($request->stages)
                ->setDocumentaryOfficesOrder($request->stages);
            $process->push();


            $this->setRequirements($request, $process);
            return response()->json([
                                        'data'    => $process->getCollectionData(),
                                        'message' => 'Proceso guardada de forma correcta.',
                                        'succes'  => true,
                                    ], 200);
        }

        /**
         * @param $request
         * @param $process
         *
         * @throws \Exception
         */
        public function setRequirements(&$request, &$process) {
            $requirements = $request->requirements_id;
            if (!empty($requirements)) {
                $delete = RequirementsRel::
                where([
                          'doc_processes_id' => $process->id,
                      ])->wherenotin('doc_files_requirements_id', $requirements)->delete();
            }
            if(!empty($requirements)) {
                foreach ($requirements as $req) {
                    $requirement = RequirementsRel::firstOrCreate([
                                                                      'doc_processes_id'          => $process->id,
                                                                      'doc_files_requirements_id' => $req,
                                                                  ]);

                }
            }
        }

        /**
         * Update the specified resource in storage.
         *
         * @param Request $request
         * @param int     $id
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function update(ProcessRequest $request, $id) {
            $process = Process::findOrFail($id);

            $process
                ->setName($request->name)
                ->setDescription($request->description)
                ->setPrice($request->price)
                ->setDocumentaryOffices($request->stages)
                ->setDocumentaryOfficesOrder($request->stages)
                ->setActive($request->active);
            $process->push();

            $this->setRequirements($request, $process);
            return response()->json([
                                        'data'    => $process->getCollectionData(),
                                        'message' => 'Proceso actualizada de forma correcta.',
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
            try {
                $process = Process::findOrFail($id);
                $process->delete();

                return response()->json([
                                            'data'    => null,
                                            'message' => 'Proceso eliminada de forma correcta.',
                                            'succes'  => true,
                                        ], 200);
            } catch (Throwable $th) {
                return response()->json([
                                            'success' => false,
                                            'data'    => 'Ocurrió un error al procesar su petición. Detalles: '.$th->getMessage(),
                                        ], 500);
            }
        }
    }
