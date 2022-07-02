<?php

namespace Modules\ApiPeruDev\Http\Controllers;

use App\Models\Tenant\Catalogs\DocumentType;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ApiPeruDev\Data\ServiceData;

class MassiveValidateController extends Controller
{
    public function tables()
    {
        $document_types = DocumentType::query()->whereIn('id', ['01', '03', '07', '08'])->get();

        return [
            'document_types' => $document_types,
        ];
    }

    public function validate(Request $request)
    {
        $company = Company::query()->first();
        $document_type_id = $request['document_type_id'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        $month_start = $request['month_start'];
        $month_end = $request['month_end'];
        $period = $request['period'];
        $d_start = null;
        $d_end = null;

        switch ($period) {
            case 'month':
                $d_start = Carbon::parse($month_start . '-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_start . '-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'between_months':
                $d_start = Carbon::parse($month_start . '-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_end . '-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'date':
                $d_start = $date_start;
                $d_end = $date_start;
                break;
            case 'between_dates':
                $d_start = $date_start;
                $d_end = $date_end;
                break;
        }

        $records = Document::query()
            ->where('soap_type_id', $company->soap_type_id)
            ->whereIn('state_type_id', ['01', '03', '05'])
            ->where('document_type_id', $document_type_id)
            ->whereBetween('date_of_issue', [$d_start, $d_end])
            ->get();

        $soap_username = '';
        $c_soap_username = $company->soap_username;
        if (strlen($c_soap_username) > 11) {
            $soap_username = substr($c_soap_username, 11, strlen($c_soap_username) - 11);
        }
        $total_documents = 0;
        $groups = $records->chunk(100);
        foreach ($groups as $group) {
            $documents = [];
            foreach ($group as $row) {
                $documents[] = [
                    "ruc_emisor" => $company->number,
                    "codigo_tipo_documento" => $row->document_type_id,
                    "serie_documento" => $row->series,
                    "numero_documento" => $row->number,
                    "fecha_de_emision" => $row->date_of_issue->format('Y-m-d'),
                    "total" => $row->total
                ];
            }

            $data = [
                "ruc_empresa" => $company->number,
                "sol_usuario" => $soap_username,
                "clave_usuario" => $company->soap_password,
                "comprobantes" => $documents
            ];

            $res = $this->sendValidate($data, $company);
            if ($res['success']) {
                $total_documents += $res['data']['count_documents'];
            }
        }

        return [
            'success' => true,
            'message' => 'Se validaron ' . $total_documents . ' comprobantes'
        ];
    }

    private function sendValidate($data, $company)
    {
        $res = (new ServiceData)->massive_validate_cpe($data);
        if ($res['success']) {
            foreach ($res['data']['comprobantes'] as $row) {
                $state_type_id = null;
                if ($row['comprobante_estado_codigo'] === '-') {
                    $state_type_id = '01';
                }
                if ($row['comprobante_estado_codigo'] === '0') {
                    $state_type_id = '01';
                }
                if ($row['comprobante_estado_codigo'] === '1') {
                    $state_type_id = '05';
                }
                if ($row['comprobante_estado_codigo'] === '2') {
                    $state_type_id = '11';
                }
                if (!is_null($state_type_id)) {
                    Document::query()->where('soap_type_id', $company->soap_type_id)
                        ->where('document_type_id', $row['codigo_tipo_documento'])
                        ->where('series', $row['serie_documento'])
                        ->where('number', $row['numero_documento'])
                        ->where('date_of_issue', $row['fecha_de_emision'])
                        ->where('total', $row['total'])
                        ->update([
                            'state_type_id' => $state_type_id
                        ]);
                }
            }
            return [
                'success' => true,
                'data' => [
                    'count_documents' => $res['data']['cantidad_de_comprobantes'],
                ]
            ];
        }

        return $res;
    }
}
