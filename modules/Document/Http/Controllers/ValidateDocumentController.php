<?php

namespace Modules\Document\Http\Controllers;

use App\Models\Tenant\Configuration;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Document;
use Modules\Document\Http\Resources\ValidateDocumentsCollection;
use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Series;
use App\Models\Tenant\StateType;
use Modules\Document\Http\Requests\ValidateDocumentsRequest;
// use App\CoreFacturalo\Services\Extras\ValidateCpe2;
use App\Models\Tenant\Company;
use Illuminate\Support\Facades\DB;
use App\CoreFacturalo\Services\IntegratedQuery\{
    AuthApi,
    ValidateCpe,
};

class ValidateDocumentController extends Controller
{

    protected $access_token;

    public function index()
    {
        return view('document::validate_documents.index');
    }


    public function records(ValidateDocumentsRequest $request)
    {


        $auth_api = (new AuthApi())->getToken();
        if(!$auth_api['success']) return $auth_api;
        $this->access_token = $auth_api['data']['access_token'];

        $records = $this->getRecords($request);
        $validate_documents = $this->validateDocuments($records);

        return new ValidateDocumentsCollection($validate_documents);

    }


    public function validateDocuments($records){

        $config = Configuration::first();
        if (empty($config)) {
            $config = new Configuration();
        }
        $records_paginate = $records->paginate($config->getNewValidatorPagination());

        // dd($this->access_token, $records_paginate->getCollection());

        foreach ($records_paginate->getCollection() as $document)
        {

            $validate_cpe = new ValidateCpe(
                                $this->access_token,
                                $document->company->number,
                                $document->document_type_id,
                                $document->series,
                                $document->number,
                                $document->date_of_issue,
                                $document->total
                            );

            $response = $validate_cpe->search();

            // dd($response);

            if ($response['success']) {

                $document->message = $response['message'];
                $document->sunat_state_type_id = $response['data']['state_type_id'];
                $document->code = $response['data']['estadoCp'];
                $document->response = $response;

            } else{

                $document->message = $response['message'];
                $document->sunat_state_type_id = null;
                $document->code = '-2';  //custom code
                $document->response = $response;

            }

        }

        return $records_paginate;
    }


    public function getRecords($request){


        $start_number = $request->start_number;
        $end_number = $request->end_number;
        $document_type_id = $request->document_type_id;
        $series = $request->series;

        if($end_number){

            $records = Document::where('document_type_id',$document_type_id)
                            ->where('series',$series)
                            ->whereBetween('number', [$start_number , $end_number])
                            ->latest();

        }else{

            $records = Document::where('document_type_id',$document_type_id)
                            ->where('series',$series)
                            ->where('number',$start_number)
                            ->latest();
        }

        return $records;

    }


    public function data_table()
    {

        $document_types = DocumentType::whereIn('id', ['01', '03','07', '08'])->get();
        $series = Series::whereIn('document_type_id', ['01', '03','07', '08'])->get();

        return compact('document_types','series');
    }


    public function regularize(ValidateDocumentsRequest $request)
    {

        $auth_api = (new AuthApi())->getToken();
        if(!$auth_api['success']) return $auth_api;
        $this->access_token = $auth_api['data']['access_token'];

        $records = $this->getRecords($request)->get();
        $state_types = StateType::get();

        $data = DB::connection('tenant')->transaction(function() use($records, $state_types){

            foreach ($records as $document)
            {
                $validate_cpe = new ValidateCpe(
                                    $this->access_token,
                                    $document->company->number,
                                    $document->document_type_id,
                                    $document->series,
                                    $document->number,
                                    $document->date_of_issue,
                                    $document->total
                                );

                $response = $validate_cpe->search();

                // dd($response, $document);

                if ($response['success']) {

                    $sunat_state_type_id = $response['data']['state_type_id'];

                    if($document->state_type_id !== $sunat_state_type_id){

                        $state_type = $state_types->first(function($state) use($sunat_state_type_id){
                            return $state->id === $sunat_state_type_id;
                        });

                        if($state_type){

                            //cpe existe - actualizando estado
                            $results [] = $this->getResult($document, 'El estado del CPE fue actualizado', true, $sunat_state_type_id);

                            $document->update([
                                'state_type_id' => $state_type->id
                            ]);

                        }else{

                            $results [] = $this->getResult($document, 'No existe en Sunat', false, $sunat_state_type_id);
                        }

                    }else{

                        $results [] = $this->getResult($document, 'Estado de sunat igual al del sistema', false, $sunat_state_type_id);
                    }


                } else{

                    //error en la busqueda
                    $results [] = $this->getResult($document, 'Error en la busqueda: '.$response['message'], false, $sunat_state_type_id);

                }
            }

            return $results;
        });

        return [
            'success' => true,
            'message' => 'Estados regularizados correctamente',
            'data' => $data
        ];

    }


    private function getResult($document, $description, $updated, $sunat_state_type_id)
    {

        return [
            'number_full' => $document->number_full,
            'description' => $description,
            'updated' => $updated,
            'initial_state' => $document->state_type_id,
            'final_state' => $sunat_state_type_id,
        ];

    }


    // public function validateDocuments($records){

    //     $records_paginate = $records->paginate(config('tenant.items_per_page'));
    //     // $documents = $records_paginate->getCollection();

    //     // dd($records_paginate->getCollection());

    //     foreach ($records_paginate->getCollection() as $document)
    //     {
    //         // evitado el buble de consulta
    //         // reValidate:
    //         $validate_cpe = new ValidateCpe2();
    //         $response = $validate_cpe->search($document->company->number,
    //                                             $document->document_type_id,
    //                                             $document->series,
    //                                             $document->number,
    //                                             $document->date_of_issue,
    //                                             $document->total
    //                                         );
    //         if ($response['success']) {

    //             $response_code = $response['data']['comprobante_estado_codigo'];
    //             $response_description = $response['data']['comprobante_estado_descripcion'];

    //             $message = $document->number_full.'|Código: '.$response_code.'|Mensaje: '.$response_description;

    //             $document->message = $message;
    //             $document->state_type_sunat_description = $response_description;
    //             $document->code = $response_code;

    //         } //else {

    //             // goto reValidate;
    //         //}
    //     }

    //     return $records_paginate;
    // }

}
