<?php

namespace Modules\Finance\Helpers;
use App\Models\Tenant\Establishment;
use App\Models\Tenant\PurchasePayment;
use Modules\Expense\Models\BankLoanPayment;
use Modules\Expense\Models\ExpensePayment;
use App\Models\Tenant\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Class ToPay
 *
 * @package Modules\Finance\Helpers
 */
class ToPay
{

    /**
     * @param $request
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getToPay($request)
    {
        $establishment_id = $request['establishment_id'] ?? 0;
        $period = $request['period'];
        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        $month_start = $request['month_start'];
        $month_end = $request['month_end'];
        $supplier_id = isset($request['supplier_id']) ? (int)$request['supplier_id'] : 0;
        $user = isset($request['user']) ? (int)$request['user'] : 0;
        // Obtendrá todos los establecimientos
        $stablishmentTopaidAll = (int)( $request['stablishmentTopaidAll'] ?? 0);
        $withBankLoan = $request['withBankLoan'] ?? 0;


        $d_start = null;
        $d_end = null;

        /** @todo: Eliminar periodo, fechas y cambiar por

        $date_start = $request['date_start'];
        $date_end = $request['date_end'];
        \App\CoreFacturalo\Helpers\Functions\FunctionsHelper\FunctionsHelper::setDateInPeriod($request, $date_start, $date_end);
         */
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

        /*
         * Purchases
         */
        $purchase_payments = DB::table('purchase_payments')
            ->select('purchase_id', DB::raw('SUM(payment) as total_payment'))
            ->groupBy('purchase_id');

        $purchases = DB::connection('tenant')
            ->table('purchases')
            ->whereIn('state_type_id', ['01', '03', '05', '07', '13'])
            ->whereIn('document_type_id', ['01', '03', 'GU75', 'NE76'])
            ->join('persons', 'persons.id', '=', 'purchases.supplier_id')
            ->leftJoinSub($purchase_payments, 'payments', function ($join) {
                $join->on('purchases.id', '=', 'payments.purchase_id');
            });
        if ($stablishmentTopaidAll !== 1) {
            if ($establishment_id != 0) {
                $purchases->where('purchases.establishment_id', $establishment_id);
            }

        }
        $select = DB::raw('purchases.id as id, ' .
            "DATE_FORMAT(purchases.date_of_issue, '%Y/%m/%d') as date_of_issue, " .
            "DATE_FORMAT(purchases.date_of_due, '%Y/%m/%d') as date_of_due, " .
            'persons.name as supplier_name, persons.id as supplier_id, purchases.document_type_id, ' .
            "CONCAT(purchases.series,'-',purchases.number) AS number_full, " .
            'purchases.total as total, ' .
            'purchases.user_id as user_id, ' .
            'IFNULL(payments.total_payment, 0) as total_payment, ' .
            "'purchase' AS 'type', " . 'purchases.currency_type_id, ' . 'purchases.exchange_rate_sale');
        $purchases->select($select);

        if ($d_start && $d_end) {
            // ->join('users', 'users.name', 'like', "%{$user}%")
            // ->where('supplier_id', $supplier_id)
            // ->where('user_id', $user)
            $purchases
                ->whereBetween('purchases.date_of_issue', [$d_start, $d_end]);

        }
        if ($supplier_id !== 0) {
            $purchases->where('supplier_id', $supplier_id);
        }

        /*
         * Sale Notes
         */
        $expense_payments = DB::table('expense_payments')
            ->select('expense_id', DB::raw('SUM(payment) as total_payment'))
            ->groupBy('expense_id');
        $expenses = DB::connection('tenant')
            ->table('expenses')
            ->join('persons', 'persons.id', '=', 'expenses.supplier_id')
            ->leftJoinSub($expense_payments, 'payments', function ($join) {
                $join->on('expenses.id', '=', 'payments.expense_id');
            })
            ->whereIn('state_type_id', ['01', '03', '05', '07', '13']);

        if ($stablishmentTopaidAll !== 1) {
            if ($establishment_id != 0) {
                $expenses->where('expenses.establishment_id', $establishment_id);
            }
        }
        $select = 'expenses.id as id, ' .
            "DATE_FORMAT(expenses.date_of_issue, '%Y/%m/%d') as date_of_issue, " .
            'null as date_of_due, ' .
            'persons.name as supplier_name, persons.id as supplier_id, null as document_type_id, ' .
            'expenses.number as number_full, ' .
            'expenses.total as total, ' .
            'expenses.user_id as user_id, ' .
            'IFNULL(payments.total_payment, 0) as total_payment, ';
        if ($d_start && $d_end) {
            $expenses
                // ->where('supplier_id', $supplier_id)
                ->select(DB::raw($select .
                    "'expense' AS 'type', " . "expenses.currency_type_id, " . "expenses.exchange_rate_sale"))
                // ->where('expenses.changed', false)
                ->whereBetween('expenses.date_of_issue', [$d_start, $d_end]);
            // ->where('expenses.total_canceled', false);

        } else {

            $expenses
                // ->where('supplier_id', $supplier_id)
                ->select(DB::raw($select .
                    "'sale_note' AS 'type', " . "expenses.currency_type_id, " . "expenses.exchange_rate_sale"));
            // ->where('expenses.changed', false)
            // ->where('expenses.total_canceled', false);
        }

        if ($supplier_id) {
            $expenses = $expenses->where('supplier_id', $supplier_id);
        }
        $bankLoans = null;

        /** Prestamos bancarios */
        if ($withBankLoan === 1) {
            $bankLoansPayments = DB::table('bank_loan_payments')
                ->select('bank_loan_id', DB::raw('SUM(payment) as total_payment'))
                ->groupBy('bank_loan_id');
            $bankLoans = DB::connection('tenant')
                ->table('bank_loans')
                ->join('bank_accounts', 'bank_accounts.id', '=', 'bank_loans.bank_account_id')
                ->leftJoinSub($bankLoansPayments, 'payments', function ($join) {
                    $join->on('bank_loans.id', '=', 'payments.bank_loan_id');
                })
                ->join('banks','bank_loans.bank_id','=','banks.id')
                ->whereIn('state_type_id', ['01', '03', '05', '07', '13']);
            $select = 'bank_loans.id as id, ' .
                "DATE_FORMAT(bank_loans.date_of_issue, '%Y/%m/%d') as date_of_issue, " .
                'null as date_of_due, ' .
                'concat(banks.description," - ",bank_accounts.description) as supplier_name,'.
                'bank_accounts.id as supplier_id,'.
                "'bank_loan'".' as document_type_id, ' .
                'bank_loans.number as number_full, ' .
                'bank_loans.total as total, ' .
                // 'bank_loans.user_id as user_id, ' .
                ' null as user_id, ' .
                'IFNULL(payments.total_payment, 0) as total_payment, ';
            if ($d_start && $d_end) {
                $bankLoans
                    // ->where('supplier_id', $supplier_id)
                    ->select(DB::raw($select .
                        "'bank_loans' AS 'type', " . "bank_loans.currency_type_id, " . "bank_loans.exchange_rate_sale"))
                    ->whereBetween('bank_loans.date_of_issue', [$d_start, $d_end]);

            } else {
                $bankLoans
                    // ->where('supplier_id', $supplier_id)
                    ->select(DB::raw($select .
                        "'bank_loans' AS 'type', " . "bank_loans.currency_type_id, " . "bank_loans.exchange_rate_sale"));
            }

            if ($supplier_id) {
                // @todo implementar filtrar por bancos
                // $bankLoans = $bankLoans->where('bank_loans.bank_id', $supplier_id);
            }
        }

        if ($user !== 0) {
            $purchases->where('user_id', $user);
            $expenses->where('user_id', $user);
        }else {
            if (auth()->user()->type !== 'admin') {
                $purchases->where('user_id', $user);
                $expenses->where('user_id', $user);

            }
        }
        $records = $purchases->union($expenses);
        if($bankLoans !== null) {
            $records->union($bankLoans);
        }
        $vars = $records->getBindings();
        $sqls = explode('?',$records->toSql());
        $sql = '' ;
        foreach($sqls as $index => $temp){
            $sql.=$temp;
            if(isset($vars[$index])){
                $sql.="'".$vars[$index]."'";

            }
        }

        $records = $records->get();

        return $records->transform(function($row) {


                $total_to_pay = (float)$row->total - (float)$row->total_payment;
                $total_subtraction = (float)$row->total_payment - (float)$row->total;
                $delay_payment = null;
                $date_of_due = null;

                if($total_to_pay > 0) {

                    if($row->date_of_due){
                        // dd($row->date_of_due);
                        $due =   Carbon::parse($row->date_of_due);
                        $date_of_due = Carbon::parse($row->date_of_due)->format('Y/m/d');
                        $now = Carbon::now();

                        if($now > $due){
                            $delay_payment = $now->diffInDays($due);
                        }


                    }
                }

                $guides = null;
                $date_payment_last = '';


                if($row->document_type_id){
                    if('bank_loan' == $row->document_type_id){
                        // para creditos bancarios
                        $date_payment_last = BankLoanPayment::where('bank_loan_id', $row->id)->orderBy('date_of_payment', 'desc')->first();

                    }else{
                        $date_payment_last = PurchasePayment::where('purchase_id', $row->id)->orderBy('date_of_payment', 'desc')->first();
                    }
                }
                else{
                    $date_payment_last = ExpensePayment::where('expense_id', $row->id)->orderBy('date_of_payment', 'desc')->first();
                }

                return [
                    'id' => $row->id,
                    'user_id' => (int) $row->user_id,
                    'date_of_issue' => $row->date_of_issue,
                    'supplier_name' => $row->supplier_name,
                    'supplier_id' => $row->supplier_id,
                    'number_full' => $row->number_full,
                    'total' => number_format((float) $row->total,2, ".", ""),
                    'total_payment' => number_format((float)$row->total_payment),
                    'total_subtraction' => $total_subtraction,

                    'total_to_pay' => number_format($total_to_pay,2, ".", ""),
                    'type' => $row->type,
                    'date_payment_last' => ($date_payment_last) ? $date_payment_last->date_of_payment->format('Y-m-d') : null,
                    'delay_payment' => $delay_payment,
                    'date_of_due' =>  $date_of_due,
                    'currency_type_id' => $row->currency_type_id,
                    'exchange_rate_sale' => (float)$row->exchange_rate_sale
                ];
        });
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getToPayNoFilter()
    {
        $purchase_payments = DB::table('purchase_payments')
                               ->select('purchase_id', DB::raw('SUM(payment) as total_payment'))
                               ->groupBy('purchase_id');
        $purchases = DB::connection('tenant')
                       ->table('purchases')
                       ->join('persons', 'persons.id', '=', 'purchases.supplier_id')
                       ->leftJoinSub($purchase_payments, 'payments', function ($join) {
                           $join->on('purchases.id', '=', 'payments.purchase_id');
                       })
                       ->whereIn('state_type_id', ['01', '03', '05', '07', '13'])
                       ->whereIn('document_type_id', ['01', '03', 'GU75', 'NE76'])
                       ->select(DB::raw("purchases.id as id, ".
                                        "DATE_FORMAT(purchases.date_of_issue, '%Y/%m/%d') as date_of_issue, ".
                                        "DATE_FORMAT(purchases.date_of_due, '%Y/%m/%d') as date_of_due, ".
                                        "persons.name as supplier_name, persons.id as supplier_id, purchases.document_type_id, ".
                                        "CONCAT(purchases.series,'-',purchases.number) AS number_full, ".
                                        "purchases.total as total, ".
                                        "IFNULL(payments.total_payment, 0) as total_payment, ".
                                        "'purchase' AS 'type', "."purchases.currency_type_id, "."purchases.exchange_rate_sale"));
        $expense_payments = DB::table('expense_payments')
                              ->select('expense_id', DB::raw('SUM(payment) as total_payment'))
                              ->groupBy('expense_id');
        $expenses = DB::connection('tenant')
                      ->table('expenses')
                      ->join('persons', 'persons.id', '=', 'expenses.supplier_id')
                      ->leftJoinSub($expense_payments, 'payments', function ($join) {
                          $join->on('expenses.id', '=', 'payments.expense_id');
                      })
                      ->whereIn('state_type_id', ['01', '03', '05', '07', '13'])
                      ->select(DB::raw("expenses.id as id, ".
                                       "DATE_FORMAT(expenses.date_of_issue, '%Y/%m/%d') as date_of_issue, ".
                                       "null as date_of_due, ".
                                       "persons.name as supplier_name, persons.id as supplier_id, null as document_type_id, ".
                                       "expenses.number as number_full, ".
                                       "expenses.total as total, ".
                                       "IFNULL(payments.total_payment, 0) as total_payment, ".
                                       "'sale_note' AS 'type', "."expenses.currency_type_id, "."expenses.exchange_rate_sale"));
        $records = $purchases->union($expenses)->get();
        return $records->transform(function ($row) {
            $total_to_pay = (float)$row->total - (float)$row->total_payment;
            $delay_payment = null;
            $date_of_due = null;
            if ($total_to_pay > 0) {
                if ($row->date_of_due) {
                    // dd($row->date_of_due);
                    $due = Carbon::parse($row->date_of_due);
                    $date_of_due = Carbon::parse($row->date_of_due)->format('Y-m-d');
                    $now = Carbon::now();
                    if ($now > $due) {
                        $delay_payment = $now->diffInDays($due);
                    }
                }
            }
            $guides = null;
            $date_payment_last = '';
            if ($row->document_type_id) {
                $date_payment_last = PurchasePayment::where('purchase_id', $row->id)->orderBy('date_of_payment', 'desc')
                                                    ->first();
            } else {
                $date_payment_last = ExpensePayment::where('expense_id', $row->id)->orderBy('date_of_payment', 'desc')
                                                   ->first();
            }
            return [
                'id'                 => $row->id,
                'date_of_issue'      => $row->date_of_issue,
                'supplier_name'      => $row->supplier_name,
                'supplier_id'        => $row->supplier_id,
                'number_full'        => $row->number_full,
                'total'              => number_format((float)$row->total, 2, ".", ""),
                'total_to_pay'       => number_format($total_to_pay, 2, ".", ""),
                'type'               => $row->type,
                'date_payment_last'  => ($date_payment_last) ? $date_payment_last->date_of_payment->format('Y-m-d')
                    : null,
                'delay_payment'      => $delay_payment,
                'date_of_due'        => $date_of_due,
                'currency_type_id'   => $row->currency_type_id,
                'exchange_rate_sale' => (float)$row->exchange_rate_sale,
            ];
//            }
        });
    }

}
