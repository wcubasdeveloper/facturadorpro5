<?php

    namespace Modules\DocumentaryProcedure\Http\Controllers;

    use Illuminate\Routing\Controller;
    use Illuminate\Support\Facades\Storage;
    use Modules\DocumentaryProcedure\Models\DocumentaryFilesArchives;

    class DocumentaryFilesArchivesController extends Controller {

        /**
         * @param \Modules\DocumentaryProcedure\Models\DocumentaryFilesArchives $id
         *
         * @return \Symfony\Component\HttpFoundation\StreamedResponse
         */
        public function download(DocumentaryFilesArchives $id) {
            $e = Storage::exists($id->getAttachedFile());
            if ($e) {
                return Storage::download($id->getAttachedFile(), $id->getPublicName());

            }
            abort(404);

        }

        /**
         * @param \Modules\DocumentaryProcedure\Models\DocumentaryFilesArchives $id
         *
         * @return \Illuminate\Http\JsonResponse
         * @throws \Exception
         */
        public function destroy(DocumentaryFilesArchives $id) {
            $file = $id->getAttachedFile();
            $data = [
                'success' => false,
                'file' => $file,
            ];
            $e = Storage::exists($file);
            if ($e) {
                Storage::delete($file);
                $id->delete();
                $data['success'] = true;
                $data['message'] = 'El registro se ha borrado';
            } else {
                if($id != null) {
                    $id->delete();
                }
                $data['success'] = true;
                $data['message'] = "El archivo no existe";
            }

            return json_encode($data);
        }

    }
