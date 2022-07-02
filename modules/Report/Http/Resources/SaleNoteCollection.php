<?php

    namespace Modules\Report\Http\Resources;

    use App\Models\Tenant\Catalogs\DocumentType;
    use App\Models\Tenant\SaleNote;
    use Illuminate\Http\Resources\Json\ResourceCollection;

    class SaleNoteCollection extends ResourceCollection
    {


        public function toArray($request)
        {

            $document_type = DocumentType::find('80');

            return $this->collection->transform(function ($row, $key) use ($document_type) {
                $document_type_id = $document_type->id;

                $row->document_type = $document_type;
                /** @var SaleNote $row */

                $affected_document = null;
                if (in_array($document_type_id, ['07', '08']) && $row->note) {

                    $series = ($row->note->affected_document) ? $row->note->affected_document->series : $row->note->data_affected_document->series;
                    $number = ($row->note->affected_document) ? $row->note->affected_document->number : $row->note->data_affected_document->number;
                    $affected_document = $series . ' - ' . $number;
                }

                $state = $row->state_type_id;

                $web_platforms = $row->getPlatformThroughItems();
                $seller = $row->getSellerData();

                return [
                    'id' => $row->id,
                    'group_id' => $row->hasAttribute('group_id') ? $row->group_id : null,
                    'soap_type_id' => $row->soap_type_id,
                    'soap_type_description' => $row->soap_type->description,
                    'date_of_issue' => $row->date_of_issue->format('Y-m-d'),
                    // 'date_of_due' => (in_array($document_type_id, ['01', '03'])) ? $row->invoice->date_of_due->format('Y-m-d') : null,
                    'date_of_due' => ($row->due_date) ? $row->due_date->format('Y-m-d') : null,

                    'number' => $row->number_full,
                    'customer_name' => $row->customer->name,
                    'customer_number' => $row->customer->number,
                    'currency_type_id' => $row->currency_type_id,
                    'series' => $row->series,
                    'establishment_id' => $row->establishment_id,
                    'alone_number' => $row->number,
                    'purchase_order' => $row->purchase_order,
                    'guides' => !empty($row->guides) ? (array)$row->guides : null,


                    'total_exportation' => (in_array($document_type_id, ['01', '03']) && in_array($row->state_type_id, ['09', '11'])) ? number_format(0, 2, ".", "") : number_format($row->total_exportation, 2, ".", ""),
                    'total_exonerated' => (in_array($document_type_id, ['01', '03']) && in_array($row->state_type_id, ['09', '11'])) ? number_format(0, 2, ".", "") : number_format($row->total_exonerated, 2, ".", ""),
                    'total_unaffected' => (in_array($document_type_id, ['01', '03']) && in_array($row->state_type_id, ['09', '11'])) ? number_format(0, 2, ".", "") : number_format($row->total_unaffected, 2, ".", ""),
                    'total_free' => (in_array($document_type_id, ['01', '03']) && in_array($row->state_type_id, ['09', '11'])) ? number_format(0, 2, ".", "") : number_format($row->total_free, 2, ".", ""),
                    'total_taxed' => (in_array($document_type_id, ['01', '03']) && in_array($row->state_type_id, ['09', '11'])) ? number_format(0, 2, ".", "") : number_format($row->total_taxed, 2, ".", ""),
                    'total_igv' => (in_array($document_type_id, ['01', '03']) && in_array($row->state_type_id, ['09', '11'])) ? number_format(0, 2, ".", "") : number_format($row->total_igv, 2, ".", ""),
                    'total' => (in_array($document_type_id, ['01', '03']) && in_array($row->state_type_id, ['09', '11'])) ? number_format(0, 2, ".", "") : number_format($row->total, 2, ".", ""),
                    'total_isc' => (in_array($document_type_id, ['01', '03']) && in_array($row->state_type_id, ['09', '11'])) ? number_format(0, 2, ".", "") : number_format($row->total_isc, 2, ".", ""),


                    'state_type_id' => $row->state_type_id,
                    'state_type_description' => $row->state_type->description,
                    'document_type_description' => $row->document_type->description,
                    'document_type_id' => $row->document_type->id ?? null,
                    'affected_document' => $affected_document,
                    'user_name' => $seller->name ?? '',
                    'user_email' => $seller->email ?? '',

                    'notes' => (in_array($document_type_id, ['01', '03'])) ? $row->affected_documents->transform(function ($row) {
                        return [
                            'id' => $row->id,
                            'document_id' => $row->document_id,
                            'note_type_description' => ($row->note_type == 'credit') ? 'NC' : 'ND',
                            'description' => $row->document->number_full,
                        ];
                    }) : null,
                    'quotation_number_full' => ($row->quotation) ? $row->quotation->number_full : '',
                    'sale_opportunity_number_full' => isset($row->quotation->sale_opportunity) ? $row->quotation->sale_opportunity->number_full : '',
                    'web_platforms' => $web_platforms,
                    'items' => $row->items->transform(function($row, $key) {
                        return [
                            'key' => $key + 1,
                            'id' => $row->id,
                            'description' => $row->item->description,
                            'quantity' => round($row->quantity,2)
                        ];
                    }),

                ];
            });
        }
    }
