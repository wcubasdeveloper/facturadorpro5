<?php

namespace App\Http\Resources\System;

use App\Models\System\TrackApiPeruServices;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {
        $currentDay = Carbon::now();
        return $this->collection->transform(function(\App\Models\System\Client $row, $key) use ($currentDay) {
            $apiPeruAsk = TrackApiPeruServices::where('client_id',$row->id)
                ->where('date_of_issue','>=',$currentDay->firstOfMonth()->format('Y-m-d'))
                ->where('date_of_issue','<=',$currentDay->lastOfMonth()->format('Y-m-d'))
                // ->whereBetween('date_of_issue',[$currentDay->firstOfMonth(),$currentDay->lastOfMonth()])
                ->get()
                ->count();
            return [
                'id' => $row->id,
                'hostname' => $row->hostname->fqdn,
                'name' => $row->name,
                'email' => $row->email,
                'token' => $row->token,
                'number' => $row->number,
                'plan' => $row->plan->name,
                'locked' => (bool) $row->locked,
                'locked_emission' => (bool) $row->locked_emission,
                'locked_users' => (bool) $row->locked_users,
                'locked_tenant' => (bool) $row->locked_tenant,
                'count_doc' => $row->count_doc,
                'max_documents' => (int) $row->plan->limit_documents,
                'count_user' => $row->count_user,
                'max_users' => (int) $row->plan->limit_users,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),

                // ciclo facturacion
                'start_billing_cycle' => ( $row->start_billing_cycle ) ? $row->start_billing_cycle->format('Y-m-d') : '',
                // 'init_cycle' => optional($row->init_cycle)->format('Y-m-d'),
                // 'end_cycle' => optional($row->end_cycle)->format('Y-m-d'),
                'count_doc_month' => $row->count_doc_month,

                'select_date_billing' => '',
                'soap_type' => $row->soap_type,
                'document_regularize_shipping' => $row->document_regularize_shipping,
                'document_not_sent' => $row->document_not_sent,
                'document_to_be_canceled' => $row->document_to_be_canceled,
                'queries_to_apiperu' => $apiPeruAsk,


            ];
        });
    }
}
