<?php

namespace Modules\Dashboard\Helpers;
use App\Models\Tenant\Document;
use App\Models\Tenant\DocumentItem;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\Dispatch;
use App\Models\Tenant\DocumentPayment;
use App\Models\Tenant\Item;
use App\Models\Tenant\SaleNote;
use App\Models\Tenant\SaleNoteItem;
use App\Models\Tenant\SaleNotePayment;
use App\Models\Tenant\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Item\Models\WebPlatform;

class DashboardView
{
    public static function getEstablishments()
    {
        return Establishment::all()->transform(function($row) {
            return [
                'id' => $row->id,
                'name' => $row->description
            ];
        });
    }

    public static function getUnpaid($request)
    {
        $establishment_id = $request['establishment_id'];
        $period = $request['period'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        $month_start = $request['month_start'];
        $month_end = $request['month_end'];
        $customer_id = $request['customer_id'];


        $d_start = null;
        $d_end = null;

        /** @todo: Eliminar periodo, fechas y cambiar por

        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        \App\CoreFacturalo\Helpers\Functions\FunctionsHelper\FunctionsHelper::setDateInPeriod($request, $date_start, $date_end);
         */
        switch ($period) {
            case 'month':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_start.'-01')->endOfMonth()->format('Y-m-d');
                break;
            case 'between_months':
                $d_start = Carbon::parse($month_start.'-01')->format('Y-m-d');
                $d_end = Carbon::parse($month_end.'-01')->endOfMonth()->format('Y-m-d');
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

        /*
         * Documents
         */
        $document_payments = DB::table('document_payments')
            ->select('document_id', DB::raw('SUM(payment) as total_payment'))
            ->groupBy('document_id');

        if($d_start && $d_end){

            $documents = DB::connection('tenant')
                ->table('documents')
                ->where('customer_id', $customer_id)
                ->join('persons', 'persons.id', '=', 'documents.customer_id')
                ->leftJoinSub($document_payments, 'payments', function ($join) {
                    $join->on('documents.id', '=', 'payments.document_id');
                })
                ->whereIn('state_type_id', ['01','03','05','07','13'])
                ->whereIn('document_type_id', ['01','03','08'])
                ->select(DB::raw("documents.id as id, ".
                                    "DATE_FORMAT(documents.date_of_issue, '%Y/%m/%d') as date_of_issue, ".
                                    "persons.name as customer_name, persons.id as customer_id, documents.document_type_id,".
                                    "CONCAT(documents.series,'-',documents.number) AS number_full, ".
                                    "documents.total as total, ".
                                    "IFNULL(payments.total_payment, 0) as total_payment, ".
                                    "'document' AS 'type', ". "documents.currency_type_id, " . "documents.exchange_rate_sale"))
                ->where('documents.establishment_id', $establishment_id)
                ->whereBetween('documents.date_of_issue', [$d_start, $d_end]);

        }else{

            $documents = DB::connection('tenant')
                ->table('documents')
                ->where('customer_id', $customer_id)
                ->join('persons', 'persons.id', '=', 'documents.customer_id')
                ->leftJoinSub($document_payments, 'payments', function ($join) {
                    $join->on('documents.id', '=', 'payments.document_id');
                })
                ->whereIn('state_type_id', ['01','03','05','07','13'])
                ->whereIn('document_type_id', ['01','03','08'])
                ->select(DB::raw("documents.id as id, ".
                                    "DATE_FORMAT(documents.date_of_issue, '%Y/%m/%d') as date_of_issue, ".
                                    "persons.name as customer_name, persons.id as customer_id, documents.document_type_id, ".
                                    "CONCAT(documents.series,'-',documents.number) AS number_full, ".
                                    "documents.total as total, ".
                                    "IFNULL(payments.total_payment, 0) as total_payment, ".
                                    "'document' AS 'type', ". "documents.currency_type_id, " . "documents.exchange_rate_sale"))
                ->where('documents.establishment_id', $establishment_id);

        }

        /*
         * Sale Notes
         */
        $sale_note_payments = DB::table('sale_note_payments')
            ->select('sale_note_id', DB::raw('SUM(payment) as total_payment'))
            ->groupBy('sale_note_id');

        if($d_start && $d_end){

            $sale_notes = DB::connection('tenant')
                ->table('sale_notes')
                ->where('customer_id', $customer_id)
                ->join('persons', 'persons.id', '=', 'sale_notes.customer_id')
                ->leftJoinSub($sale_note_payments, 'payments', function ($join) {
                    $join->on('sale_notes.id', '=', 'payments.sale_note_id');
                })
                ->whereIn('state_type_id', ['01','03','05','07','13'])
                ->select(DB::raw("sale_notes.id as id, ".
                                "DATE_FORMAT(sale_notes.date_of_issue, '%Y/%m/%d') as date_of_issue, ".
                                "persons.name as customer_name, persons.id as customer_id, null as document_type_id,".
                                "sale_notes.filename as number_full, ".
                                "sale_notes.total as total, ".
                                "IFNULL(payments.total_payment, 0) as total_payment, ".
                                "'sale_note' AS 'type', " . "sale_notes.currency_type_id, " . "sale_notes.exchange_rate_sale"))
                ->where('sale_notes.establishment_id', $establishment_id)
                ->where('sale_notes.changed', false)
                ->whereBetween('sale_notes.date_of_issue', [$d_start, $d_end])
                ->where('sale_notes.total_canceled', false);

        }else{

            $sale_notes = DB::connection('tenant')
                ->table('sale_notes')
                ->where('customer_id', $customer_id)
                ->join('persons', 'persons.id', '=', 'sale_notes.customer_id')
                ->leftJoinSub($sale_note_payments, 'payments', function ($join) {
                    $join->on('sale_notes.id', '=', 'payments.sale_note_id');
                })
                ->whereIn('state_type_id', ['01','03','05','07','13'])
                ->select(DB::raw("sale_notes.id as id, ".
                                "DATE_FORMAT(sale_notes.date_of_issue, '%Y/%m/%d') as date_of_issue, ".
                                "persons.name as customer_name, persons.id as customer_id, null as document_type_id,".
                                "sale_notes.filename as number_full, ".
                                "sale_notes.total as total, ".
                                "IFNULL(payments.total_payment, 0) as total_payment, ".
                                "'sale_note' AS 'type', " . "sale_notes.currency_type_id, " . "sale_notes.exchange_rate_sale"))
                ->where('sale_notes.establishment_id', $establishment_id)
                ->where('sale_notes.changed', false)
                ->where('sale_notes.total_canceled', false);

        }

        $records = $documents->union($sale_notes)->get();

        return collect($records)->transform(function($row) {
                $total_to_pay = (float)$row->total - (float)$row->total_payment;
                $delay_payment = null;
                $date_of_due = null;

                if($total_to_pay > 0) {
                    if($row->document_type_id){

                        $invoice = Invoice::where('document_id', $row->id)->first();
                        if($invoice)
                        {
                            $due =   Carbon::parse($invoice->date_of_due); // $invoice->date_of_due;
                            $date_of_due = $invoice->date_of_due->format('Y/m/d');
                            $now = Carbon::now();

                            if($now > $due){

                                $delay_payment = $now->diffInDays($due);
                            }


                        }
                    }
                }

                $guides = null;
                $date_payment_last = '';

                if($row->document_type_id){
                    $guides =  Dispatch::where('reference_document_id', $row->id )->orderBy('series')->orderBy('number', 'desc')->get()->transform(function($item) {
                        return [
                            'id' => $item->id,
                            'external_id' => $item->external_id,
                            'number' => $item->number_full,
                            'date_of_issue' => $item->date_of_issue->format('Y-m-d'),
                            'date_of_shipping' => $item->date_of_shipping->format('Y-m-d'),
                            'download_external_xml' => $item->download_external_xml,
                            'download_external_pdf' => $item->download_external_pdf,
                        ];
                    });

                    $date_payment_last = DocumentPayment::where('document_id', $row->id)->orderBy('date_of_payment', 'desc')->first();
                }
                else{
                    $date_payment_last = SaleNotePayment::where('sale_note_id', $row->id)->orderBy('date_of_payment', 'desc')->first();
                }

                return [
                    'id' => $row->id,
                    'date_of_issue' => $row->date_of_issue,
                    'customer_name' => $row->customer_name,
                    'customer_id' => $row->customer_id,
                    'number_full' => $row->number_full,
                    'total' => number_format((float) $row->total,2, ".", ""),
                    'total_to_pay' => number_format($total_to_pay,2, ".", ""),
                    'type' => $row->type,
                    'guides' => $guides,
                    'date_payment_last' => ($date_payment_last) ? $date_payment_last->date_of_payment->format('Y-m-d') : null,
                    'delay_payment' => $delay_payment,
                    'date_of_due' =>  $date_of_due,
                    'currency_type_id' => $row->currency_type_id,
                    'exchange_rate_sale' => (float)$row->exchange_rate_sale
                ];
//            }
        });
    }

    public static function getUnpaidFilterUser($request)
    {
        $establishment_id = $request['establishment_id']??null;
        $period = $request['period']??null;
        $date_start = $request['date_start']??null;
        $date_end = $request['date_end']??null;
        $month_start = $request['month_start']??null;
        $month_end = $request['month_end']??null;
        $customer_id = $request['customer_id']??null;
        $user_id = $request['user_id']??null;
        $web_platform_id = $request['web_platform_id']??0;
        $purchase_order = $request['purchase_order']??null;
        $payment_method_type_id = $request['payment_method_type_id']??null;
        // Obtendrá todos los establecimientos
        $stablishmentUnpaidAll = $request['stablishmentUnpaidAll']??0;
        $user = auth()->user();
        if(null === $user){
            $user = new \App\Models\Tenant\User();
        }
        $user_type = $user->type;
        $user_id_session = $user->id;
        $d_start = null;
        $d_end = null;

        /** @todo: Eliminar periodo, fechas y cambiar por

        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        \App\CoreFacturalo\Helpers\Functions\FunctionsHelper\FunctionsHelper::setDateInPeriod($request, $date_start, $date_end);
         */switch ($period) {
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
        // dd($request['customer_id']);
        /*
         * Documents
         */
        $document_payments = DB::table('document_payments')
            ->select('document_id', DB::raw('SUM(payment) as total_payment'))
            ->groupBy('document_id');

        $document_select = "documents.id as id, " .
            "DATE_FORMAT(documents.date_of_issue, '%Y/%m/%d') as date_of_issue, " .
            "persons.name as customer_name,".
            "persons.id as customer_id,".
            "documents.document_type_id," .
            "CONCAT(documents.series,'-',documents.number) AS number_full, " .
            "documents.total as total, " .
            "IFNULL(payments.total_payment, 0) as total_payment, " .
            "IFNULL(credit_notes.total_credit_notes, 0) as total_credit_notes, " .
            "documents.total - IFNULL(total_payment, 0) - IFNULL(total_credit_notes, 0)  as total_subtraction, " .
            "'document' AS 'type', " .
            "documents.currency_type_id, " .
            "documents.exchange_rate_sale, " .
            " documents.user_id, " .
            "users.name as username";

        $sale_note_select = "sale_notes.id as id, " .
            "DATE_FORMAT(sale_notes.date_of_issue, '%Y/%m/%d') as date_of_issue, " .
            "persons.name as customer_name,".
            "persons.id as customer_id,".
            "null as document_type_id," .
            "sale_notes.filename as number_full, " .
            "sale_notes.total as total, " .
            "IFNULL(payments.total_payment, 0) as total_payment, " .
            "null as total_credit_notes," .
            "sale_notes.total - IFNULL(total_payment, 0)  as total_subtraction, " .
            "'sale_note' AS 'type', " .
            "sale_notes.currency_type_id, " .
            "sale_notes.exchange_rate_sale, " .
            " sale_notes.user_id, " .
            "users.name as username";

        $documents = DB::connection('tenant')
            ->table('documents')
            //->where('customer_id', $customer_id)
            ->join('persons', 'persons.id', '=', 'documents.customer_id')
            ->join('users', 'users.id', '=', 'documents.user_id')
            ->leftJoinSub($document_payments, 'payments', function ($join) {
                $join->on('documents.id', '=', 'payments.document_id');
            })
            ->leftJoinSub(Document::getQueryCreditNotes(), 'credit_notes', function ($join) {
                $join->on('documents.id', '=', 'credit_notes.affected_document_id');
            })
            ->whereIn('state_type_id', ['01', '03', '05', '07', '13'])
            ->whereIn('document_type_id', ['01', '03', '08'])
            ->select(DB::raw($document_select));

        // dd($documents->get());
        // dd($documents->toSql());

        if($stablishmentUnpaidAll !== 1) {
            $documents-> where('documents.establishment_id', $establishment_id);
        }

        if ($payment_method_type_id) {
            $documents->where('payment_method_type_id', $payment_method_type_id);
        }
        /*
         * Sale Notes
         */
        $sale_note_payments = DB::table('sale_note_payments')
            ->select('sale_note_id', DB::raw('SUM(payment) as total_payment'))
            ->groupBy('sale_note_id');


        $sale_notes = DB::connection('tenant')
            ->table('sale_notes')
            // ->where('customer_id', $customer_id)
            ->join('persons', 'persons.id', '=', 'sale_notes.customer_id')
            ->join('users', 'users.id', '=', 'sale_notes.user_id')
            ->leftJoinSub($sale_note_payments, 'payments', function ($join) {
                $join->on('sale_notes.id', '=', 'payments.sale_note_id');
            })
            ->whereIn('state_type_id', ['01', '03', '05', '07', '13'])
            ->select(DB::raw($sale_note_select))
            ->where('sale_notes.changed', false)
            ->where('sale_notes.total_canceled', false);

        if($stablishmentUnpaidAll !== 1) {
            $sale_notes
                ->where('sale_notes.establishment_id', $establishment_id)
            ;
        }

        if ($user_type == 'seller') {
            $sale_notes->where('user_id', $user_id_session);
            $documents->where('user_id', $user_id_session);
        }
        if ($user_type == 'admin' && $user_id) {
            $sale_notes->whereIn('user_id', [$user_id_session, $user_id]);
            $documents->whereIn('user_id', [$user_id_session, $user_id]);
        }
        if ($customer_id) {
            $sale_notes->where('customer_id', $customer_id);
            $documents->where('customer_id', $customer_id);
        }
        if ($d_start && $d_end) {
            $sale_notes->whereBetween('sale_notes.date_of_issue', [$d_start, $d_end]);
            $documents->whereBetween('documents.date_of_issue', [$d_start, $d_end]);
        }
        // return $documents->union($sale_notes);
        if($purchase_order !== null){
            $documents->where('purchase_order',$purchase_order);
            $sale_notes->where('purchase_order',$purchase_order);
        }
        if($web_platform_id != 0){
            $web_platform_table_name = (new WebPlatform())->getTable();
            $item_table_name = (new Item())->getTable();
            $document_item_table = (new DocumentItem())->getTable();
            $sale_note_item_table = (new SaleNoteItem())->getTable();
            // Se busca las plataformas de los items para obtener los documentos respectivos.
            $document_items_id = DocumentItem::
            leftJoin($item_table_name, "$item_table_name.id", '=', "$document_item_table.item_id")
                ->leftJoin($web_platform_table_name, "$web_platform_table_name.id", '=', "$item_table_name.web_platform_id")
                ->where("$item_table_name.web_platform_id", $web_platform_id)
                ->select($document_item_table . '.document_id as document_id')
                ->get()
                ->pluck('document_id');
            $documents->wherein('documents.id', $document_items_id);

            $sale_note_items_id = SaleNoteItem::
            leftJoin($item_table_name, "$item_table_name.id", '=', "$sale_note_item_table.item_id")
                ->leftJoin($web_platform_table_name, "$web_platform_table_name.id", '=', "$item_table_name.web_platform_id")
                ->where("$item_table_name.web_platform_id", $web_platform_id)
                ->select($sale_note_item_table . '.sale_note_id as document_id')
                ->get()
                ->pluck('document_id');
            $sale_notes->wherein('sale_notes.id', $sale_note_items_id);

        }
        return $documents->union($sale_notes)->havingRaw('total_subtraction > 0');
    }



}
