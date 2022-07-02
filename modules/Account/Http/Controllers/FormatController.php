<?php

namespace Modules\Account\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Catalogs\CurrencyType;
use App\Models\Tenant\Company;
use App\Models\Tenant\Document;
use App\Models\Tenant\Purchase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Account\Exports\ReportFormatPurchaseExport;
use Modules\Account\Exports\ReportFormatSaleExport;
use Modules\Account\Exports\ReportFormatSaleGarageGllExport;
use App\Models\Tenant\{
    DocumentItem
};


    /**
     * Class FormatController
     *
     * @package Modules\Account\Http\Controllers
     */
    class FormatController extends Controller {
        /**
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
         */
        public function index() {
            $currencies = CurrencyType::select(
                'id',
                'symbol',
                'description'
            )->Actives()->get();
            return view('account::account.format', compact('currencies'));
    }

        /**
         * @param \Illuminate\Http\Request $request
         *
         * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
         */
        public function download(Request $request) {
        $type = $request->input('type');
        $month = $request->input('month');

        $d_start = Carbon::parse($month.'-01')->format('Y-m-d');
        $d_end = Carbon::parse($month.'-01')->endOfMonth()->format('Y-m-d');

            $company = $this->getCompany();

            $filename = 'Reporte_Formato_Compras_'.date('YmdHis');
            $data = [
                'period' => $month,
                'company' => $company,
                'params' => $request->all(),
            ];

            if ($type === 'sale') 
            {
                $filename = 'Reporte_Formato_Ventas_'.date('YmdHis');
                $data['records'] = $this->getSaleDocuments($d_start, $d_end);
                $reportFormatSaleExport = new ReportFormatSaleExport();
                $reportFormatSaleExport->data($data);
                // return $reportFormatSaleExport->view();
                return $reportFormatSaleExport
                    ->download($filename.'.xlsx');
            }
            else if($type === 'garage-gll')
            {
                
                $data['records'] = $this->getSaleGarageGll($d_start, $d_end);
                return (new ReportFormatSaleGarageGllExport())->data($data)->download('Reporte_Formato_Ventas_Grifo'.date('YmdHis').'.xlsx');
            }

            $data['records'] = $this->getPurchaseDocuments($d_start, $d_end);

            $reportFormatPurchaseExport = new ReportFormatPurchaseExport();
            $reportFormatPurchaseExport->data($data);
            // return $reportFormatPurchaseExport->view();
            return $reportFormatPurchaseExport
                ->download($filename.'.xlsx');
        }

        /**
         * @return array
         */
        private function getCompany() {
        $company = Company::query()->first();

        return [
            'name' => $company->name,
            'number' => $company->number,
        ];
    }

        
        /**
         * 
         * Datos para reporte grifo
         *
         * @param $d_start
         * @param $d_end
         * @return array
         */
        private function getSaleGarageGll($d_start, $d_end)
        {
            return DocumentItem::filterSaleGarageGLL($d_start, $d_end)->get();
        }


        /**
         * @param                                               $d_start
         * @param                                               $d_end
         *
         * @return \App\Models\Tenant\Document[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection|mixed
         */
        private function getSaleDocuments($d_start, $d_end) {
            $data = Document::query()
                            ->whereBetween('date_of_issue', [$d_start, $d_end])
                // ->whereIn('document_type_id', ['01', '03'])
                // ->whereIn('currency_type_id', ['PEN', 'USD'])
                            ->orderBy('series')
                            ->orderBy('number')
                            ->get()
                            ->transform(function ($row) {
                                /** @var \App\Models\Tenant\Document $row */
                                $row = $this->AdjustValueToReportByDocumentTypeAndStateType($row);
                                $note_affected_document = new Document();
                                if (!empty($row->note)) {
                                    if (!empty($row->note->affected_document)) {
                                        $note_affected_document = $row->note->affected_document;
                                        $row = $this->AdjustValueToReportByDocumentTypeAndStateType($row, 1);
                                    } elseif (!empty($row->note->data_affected_document)) {
                                        $data_affected_document = (array)$row->note->data_affected_document;
                                        $note_affected_document = Document::where([
                                                                                      'number'           => $data_affected_document['number'],
                                                                                      'series'           => $data_affected_document['series'],
                                                                                      'document_type_id' => $data_affected_document['document_type_id'],
                                                                                  ])->first();
                                        if (!empty($note_affected_document)) {
                                            $row = $this->AdjustValueToReportByDocumentTypeAndStateType($row, 1);
                                        } else {
                                            $note_affected_document = new Document($data_affected_document);
                                            $row = $this->AdjustValueToReportByDocumentTypeAndStateType($row);

                                        }
                                    }
                                }
                                $symbol = $row->currency_type->symbol;

                                $total = round($row->total, 2);
                                $total_taxed = round($row->total_taxed, 2);
                                $total_igv = round($row->total_igv, 2);
                                $total_exonerated = $row->total_exonerated;
                                $total_unaffected = $row->total_unaffected;
                                $total_exportation = $row->total_exportation;
                                $total_isc = $row->total_isc;

                                $exchange_rate_sale = $row->exchange_rate_sale;
                                $currency_type_id = $row->currency_type_id;
                                $format_currency_type_id = $row->currency_type_id;

                                // aplicar conversion al tipo de cambio
                                if ($row->currency_type_id === 'USD') 
                                {
                                    $total = round($row->generalConvertValueToPen($total, $exchange_rate_sale), 2);
                                    $total_taxed = round($row->generalConvertValueToPen($total_taxed, $exchange_rate_sale), 2);
                                    $total_igv = round($row->generalConvertValueToPen($total_igv, $exchange_rate_sale), 2);
                                    $total_exonerated = round($row->generalConvertValueToPen($total_exonerated, $exchange_rate_sale), 2);
                                    $total_unaffected = round($row->generalConvertValueToPen($total_unaffected, $exchange_rate_sale), 2);
                                    $total_exportation = round($row->generalConvertValueToPen($total_exportation, $exchange_rate_sale), 2);
                                    $total_isc = round($row->generalConvertValueToPen($total_isc, $exchange_rate_sale), 2);
                                    $symbol = 'S/';
                                    $format_currency_type_id = 'PEN';
                                }


                                return [
                                    'date_of_issue'                      => $row->date_of_issue->format('d/m/Y'),
                                    'document_type_id'                   => $row->document_type_id,
                                    'state_type_id'                      => $row->state_type_id,
                                    'state_type_description'             => $row->state_type->description,
                                    'series'                             => $row->series,
                                    'number'                             => $row->number,
                                    'customer_identity_document_type_id' => $row->customer->identity_document_type_id,
                                    'customer_number'                    => $row->customer->number,
                                    'customer_name'                      => $row->customer->name,
                                    'total_exportation'                  => $total_exportation,
                                    'total_taxed'                        => $total_taxed,
                                    'total_exonerated'                   => $total_exonerated,
                                    'total_unaffected'                   => $total_unaffected,
                                    'total_plastic_bag_taxes'            => $row->total_plastic_bag_taxes,
                                    'total_isc'                          => $total_isc,
                                    'total_igv'                          => $total_igv,
                                    'total'                              => $total,
                                    // 'selected_currency'                              => $currencyRequested,
                                    'exchange_rate_sale'                 => $exchange_rate_sale,
                                    'currency_type_symbol'               => $symbol,
                                    'format_currency_type_id'            => $format_currency_type_id,
                                    'affected_document'                  => (in_array($row->document_type_id,
                                                                                      ['07', '08'])) ? [
                                        'date_of_issue'    => !empty($note_affected_document->date_of_issue)
                                            ? $note_affected_document->date_of_issue->format('d/m/Y') : null,
                                        'document_type_id' => $note_affected_document->document_type_id,
                                        'series'           => $note_affected_document->series,
                                        'number'           => $note_affected_document->number,

                                    ] : null,
                                ];
                            });

            return $data;

        }

    /**
     * Establece a 0 los totales para los documentos que se habiliten en $type_document_to_evalue
     * y que el status se encuentre en $type_document_to_evalue.
     *
     * Normalmente se evalua Factura electronica (01) y Boleta de venta electronica (03)
     * Si $is_affected es verdadero, evalua tambien nota de credito (07) y debito (08)
     *
     * @param Document $row
         * @param bool     $is_affected
     *
     * @return Document
     */
    public function AdjustValueToReportByDocumentTypeAndStateType(Document $row, $is_affected = false){

        $document_type_id = $row->document_type_id;
        $state_type_id = $row->state_type_id;
        $type_document_to_evalue = [
            '01',//    FACTURA ELECTRÓNICA
            '03',//    BOLETA DE VENTA ELECTRÓNICA
            //'07',//    NOTA DE CRÉDITO
            //'08',//    NOTA DE DÉBITO
            //'09',//    GUIA DE REMISIÓN REMITENTE
            //'20',//    COMPROBANTE DE RETENCIÓN ELECTRÓNICA
            //'31',//    Guía de remisión transportista
            //'40',//    COMPROBANTE DE PERCEPCIÓN ELECTRÓNICA
            //'71',//    Guia de remisión remitente complementaria
            //'72',//	Guia de remisión transportista complementaria
            //'GU75',//	GUÍA
            //'NE76',//	NOTA DE ENTRADA
            //'80',//	NOTA DE VENTA
            //'02',//	RECIBO POR HONORARIOS
            //'14',//	SERVICIOS PÚBLICOS
        ];
        if($is_affected == true){
            $type_document_to_evalue = [
                '01',//    FACTURA ELECTRÓNICA
                '03',//    BOLETA DE VENTA ELECTRÓNICA
                '07',//    NOTA DE CRÉDITO
                '08',//    NOTA DE DÉBITO
            ];
        }
        $document_state_to_evalue = [
            // '01',//	Registrado
            // '03',//	Enviado
            // '05',//	Aceptado
            // '07',//	Observado
            '09',//	Rechazado
            '11',//	Anulado
            // '13',//	Por anular
        ];
        if (
            in_array($document_type_id, $type_document_to_evalue) &&
            in_array($state_type_id, $document_state_to_evalue)
        ) {
            $row->total_exportation = 0 ;
            $row->total_taxed = 0 ;
            $row->total_exonerated = 0 ;
            $row->total_unaffected = 0 ;
            $row->total_plastic_bag_taxes = 0 ;
            $row->total_igv = 0 ;
            $row->total = 0 ;
        }
        return $row;
    }

        /**
         * @param                                               $d_start
         * @param                                               $d_end
         *
         * @return \App\Models\Tenant\Purchase[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Query\Builder[]|\Illuminate\Support\Collection|mixed
         */
        private function getPurchaseDocuments($d_start, $d_end) {
            $data = Purchase::query()
            ->whereBetween('date_of_issue', [$d_start, $d_end])
            ->whereIn('document_type_id', ['01', '03', '14'])
                // ->whereIn('currency_type_id', ['PEN','USD'])
            ->orderBy('series')
            ->orderBy('number')
                            ->get()
                            ->transform(function ($row) {
                                /** @var \App\Models\Tenant\Purchase $row */
                                $symbol = $row->currency_type->symbol;
                                $currency_type_id = $row->currency_type_id;


                                $total = round($row->total, 2);
                                $total_taxed = round($row->total_taxed, 2);
                                $total_igv = round($row->total_igv, 2);
                                $exchange_rate_sale = $row->exchange_rate_sale;
                                // $exchange_rate_sale = round($row->exchange_rate_sale, 2);
                                $total_exportation = round($row->total_exportation, 2);
                                $total_exonerated = round($row->total_exonerated, 2);
                                $total_unaffected = round($row->total_unaffected, 2);
                                $total_isc = round($row->total_isc, 2);

                return [
                    'date_of_issue' => $row->date_of_issue->format('d/m/Y'),
                    'date_of_due' => $row->date_of_due->format('d/m/Y'),
                    'state_type_id' => $row->state_type_id,
                    'document_type_id' => $row->document_type_id,
                    'series' => $row->series,
                    'number' => $row->number,
                    'supplier_identity_document_type_id' => $row->supplier->identity_document_type_id,
                    'supplier_number' => $row->supplier->number,
                    'supplier_name' => $row->supplier->name,
                                    'total_exportation'                  => $total_exportation,
                                    'total_exonerated'                   => $total_exonerated,
                                    'total_unaffected'                   => $total_unaffected,
                                    'total_isc'                          => $total_isc,
                                    'total_taxed'                        => $total_taxed,
                                    'total_igv'                          => $total_igv,
                                    'total'                              => $total,
                                    'exchange_rate_sale'                 => $exchange_rate_sale,
                                    'currency_type_symbol'               => $symbol,
                ];
            });
            return $data;

    }
}
