<?php

    namespace Modules\Account\Models;

    use App\Models\Tenant\Item;
    use App\Models\Tenant\ModelTenant;
    use Carbon\Carbon;
    use Hyn\Tenancy\Traits\UsesTenantConnection;
    use Illuminate\Http\Request;
    use Modules\Dashboard\Helpers\DashboardView;
    use Modules\Finance\Helpers\ToPay;
    use Modules\Finance\Http\Controllers\BalanceController;
    use Modules\Inventory\Http\Controllers\ReportValuedKardexController;

    /**
     * Class AccountingLedger
     *
     * @property int         $id
     * @property int|null    $month
     * @property int|null    $year
     * @property Carbon|null $date_of_report
     * @property string      $code_account
     * @property string      $name
     * @property float       $last_month_total
     * @property float       $credits
     * @property float       $debs
     * @property float       $final_total
     * @property string      $serialize_data
     * @property Carbon|null $created_at
     * @property Carbon|null $updated_at
     * @mixin  ModelTenant
     * @package Modules\Account\Models
     */
    class AccountingLedger extends ModelTenant
    {
        use UsesTenantConnection;

        protected $table = 'accounting_ledger';
        protected $perPage = 25;
        protected $casts = [
            'month' => 'int',
            'year' => 'int',
            'last_month_total' => 'float',
            'credits' => 'float',
            'debs' => 'float',
            'final_total' => 'float',
        ];
        protected $fillable = [
            'month',
            'year',
            'date_of_report',
            'code_account',
            'name',
            'last_month_total',
            'credits',
            'debs',
            'final_total',
            'serialize_data',
        ];

        /*
        protected $dates = [
            'date_of_report'
        ];
        */

        /**
         * Es llamado desde app/Console/Commands/AccountLedgerFillCommand.php y  modules/Account/Http/Controllers/LedgerAccountController.php
         * @param Carbon $month
         *
         * @return array
         */
        public static function saveData(Carbon $month): array
        {

            $request = new Request();


            $cMon = $month->format('Y-m');
            $request->merge([
                'date_end' => $month->lastOfMonth(),
                'date_start' => $month->firstOfMonth(),
                'month_end' => $cMon,
                'month_start' => $cMon,
                'period' => 'month',
            ]);
            $requestArray = $request->all();
            $requestArray['stablishmentKardexAll'] = 1;
            $kardex = new ReportValuedKardexController();
            $totalSaleKardex = 0;
            $totalPurchasesKardex = 0;
            $finalTotalKardex = 0;
            $kardex
                ->getRecords($requestArray)
                ->get()
                ->transform(function (Item $row) use (
                    $totalSaleKardex,
                    $totalPurchasesKardex,
                    $finalTotalKardex
                ) {
                    $data = $row->getReportValuedKardexCollection();
                    $data['total_sales'] = $data['total_sales'] ?? 0;
                    $totalSaleKardex += abs((float)$data['total_sales']);

                    $data['item_cost'] = $data['item_cost'] ?? 0;
                    $totalPurchasesKardex += abs((float)$data['item_cost']);

                    $data['valued_unit'] = $data['valued_unit'] ?? 0;
                    $finalTotalKardex += abs((float)$data['valued_unit']);
                    return $data;
                });

            $record = [];
            $items = [];
            $now = Carbon::now();
            $currentYear = $now->format('Y');
            $currentMonth = $now->format('m');
            /*
             * // pland e cuenta https://www.mef.gob.pe/contenidos/conta_publ/documentac/VERSION_MODIFICADA_PCG_EMPRESARIAL.pdf
            */


            $dates = explode('-', $requestArray['month_start']);
            $date_of_report = Carbon::createFromFormat('m-Y', $dates[1] . "-" . $dates[0]);
            // Cuentas por pagar
            $requestArray['stablishmentUnpaidAll'] = 1;
            $cuentasPorCobrar = DashboardView::getUnpaidFilterUser($requestArray)->get();
            $requestArray['stablishmentTopaidAll'] = 1;
            $cuentasPorPagar = ToPay::getToPay($requestArray);

            // Se obtiene las cuentas registradas en el sistema
            $cuentasContables = AccountingLedgerCodeAccount::all()->transform(function (AccountingLedgerCodeAccount $row) use ($dates) {
                return [
                    'month' => $dates[1],
                    'year' => $dates[0],
                    // 'last_month_total' => 0,
                    'credits' => 0,
                    'debs' => 0,
                    'code_account' => $row->code_account,
                    'final_total' => 0,
                    'date_of_report' => implode('-', $dates),
                    'name' => trim($row->name),
                    'serialize_data' => serialize($row),
                ];
            });
            foreach ($cuentasContables as $contable) {
                $record[$contable['code_account']] = $contable;
            }


            $record['1.1.3.1'] = [
                'month' => $dates[1],
                'year' => $dates[0],
                'code_account' => '1.1.3.1',
                // 'last_month_total' => $items['initial_balance'] ?? 0,
                'credits' => $totalSaleKardex,
                'debs' => $totalPurchasesKardex,
                'final_total' => $finalTotalKardex,
                'date_of_report' => $requestArray['date_end'],
                'name' => AccountingLedgerCodeAccount::getNameByCodeAcount('1.1.3.1', 'Inventario de productos'),
            ];
            $record['1.1.3.1']['serialize_data'] = serialize($record['1.1.3.1']); //


            $record['1.1.2.1'] = [
                'month' => $dates[1],
                'year' => $dates[0],
                'code_account' => '1.1.2.1',
                // 'last_month_total' => $items['initial_balance'] ?? 0,
                'credits' => $cuentasPorCobrar->sum('total_payment'),
                'debs' => $cuentasPorCobrar->sum('total_subtraction'),
                'final_total' => $cuentasPorCobrar->sum('total_to_pay'),
                'date_of_report' => $requestArray['date_end'],
                'name' => AccountingLedgerCodeAccount::getNameByCodeAcount('1.1.2.1', 'Cuentas por cobrar clientes'),
            ];
            $record['1.1.2.1']['serialize_data'] = serialize($record['1.1.2.1']); //
            try{
                $total_payment = $cuentasPorPagar->sum('total_payment');
            }catch (\ErrorException $e){
                $total_payment = 0;
            }
            $record['2.1.1.1'] = [

                'month' => $dates[1],
                'year' => $dates[0],
                'code_account' => '2.1.1.1',
                // 'last_month_total' => $items['initial_balance'] ?? 0,
                'credits' =>$total_payment,
                'debs' => $cuentasPorPagar->sum('total_subtraction'),
                'final_total' => $cuentasPorPagar->sum('total_to_pay'),
                'date_of_report' => $requestArray['date_end'],
                'name' => AccountingLedgerCodeAccount::getNameByCodeAcount('2.1.1.1', 'Cuentas por pagar a proveedores'),

                'serialize_data' => serialize($cuentasPorPagar),
            ]; //
            $record['2.1.1.1']['serialize_data'] = serialize($record['2.1.1.1']);
            // Activos
            $balanceController = new BalanceController();
            $activos = $balanceController->getRecords($request);
            /*
                        $retentions = $this->getRetentions($request)->get();
                        2.1.4.2.1										                Detracción por pagar
                    */


            $debsSale = 0;
            $creditsSale = 0;

            foreach ($activos as $index => $items) {
                $target = ($items['id'] == 'cash') ? 1 : 2;
                $currentValue = ($items['id'] == 'cash') ? 1 : (int)$items['id'];
                $plan_cuenta = "1.1.1.$target.$currentValue";
                $temp = [
                    'month' => $dates[1],
                    'year' => $dates[0],
                    'code_account' => $plan_cuenta,
                    // 'last_month_total' => $items['initial_balance'] ?? 0,
                    'credits' => $items['credits'] ?? 0,
                    'debs' => $items['debs'] ?? 0,
                    'final_total' => $items['balance'] ?? 0,
                    'date_of_report' => $requestArray['date_end'],
                    'name' => $items['description'] ?? '',
                    'serialize_data' => serialize($items),
                ];
                $record["$plan_cuenta"] = $temp;
                $creditsSale += abs($temp['credits']);
                $debsSale += abs($temp['debs']);
            }
            // esta mal, debe ser la sumatoria de los documentos NV y factura/boleta
            $pc = "4.1.1";
            $pcName = 'Ventas';
            $record[$pc] = [
                'month' => $dates[1],
                'year' => $dates[0],
                'code_account' => $pc,
                // 'last_month_total' => $items['initial_balance'] ?? 0,
                'credits' => $creditsSale,
                'debs' => $debsSale,
                'final_total' => ($creditsSale - $debsSale),
                'date_of_report' => $requestArray['date_end'],
                'name' => AccountingLedgerCodeAccount::getNameByCodeAcount($pc, $pcName),
            ];
            $record[$pc]['serialize_data'] = serialize($record[$pc]);

            $records = [];

            foreach ($record as $code => $acount) {
                $acount['date_of_report'] = $date_of_report->format('Y-m-d');
                $find = self::where([
                    "month" => $acount['month'],
                    "year" => $acount['year'],
                    "code_account" => $acount['code_account'],
                ])->first();
                if ($find == null) {
                    $find = new AccountingLedger($acount);
                }
                $find->push();
                $records [] = $find->toArray();
            }

            return collect($records)->sortBy('code_account')->toArray();
        }

        protected static function boot()
        {

            parent::boot();

            static::saving(function (self $model) {
                self::fixFinalTotal($model);
            });

        }

        public static function fixFinalTotal(&$model)
        {
            /** @var self $model */

            $month = $model->month;
            $year = $model->year;

            $date = Carbon::createFromFormat('Y-m', "$year-$month")->addMonth(-1);
            $account = self::where([
                'code_account' => $model->code_account,
                'month' => $date->format('m'),
                'year' => $date->format('Y'),
            ])->first();

            if (null !== $account) {
                $model->last_month_total = $account->final_total;
            }
            $model->name = trim($model->name);
            $model->final_total = ($model->credits - $model->debs) + $model->last_month_total;
            // Se establecen los montos en positivo
            $model->last_month_total = abs($model->last_month_total);
            $model->credits = abs($model->credits);
            $model->debs = abs($model->debs);
            $model->final_total = abs($model->final_total);
            $accountLedger = AccountingLedgerCodeAccount::where(['code_account' => $model->code_account,])->first();
            if (!empty($accountLedger)) {
                // sumar todos los hijos de la cuenta

                $excludeaAccounts = AccountingLedgerCodeAccount::query()->select('code_account')->get()->pluck('code_account'); // Se omiten las cuentas principales
                $acoounts = self::where(
                    'code_account', 'like', $model->code_account . ".%")->where([
                    'month' => $date->format('m'),
                    'year' => $date->format('Y'),
                ])
                    ->whereNotIn('code_account', $excludeaAccounts)
                    ->get();

                $last_month_total = $acoounts->sum('last_month_total');
                $credits = $acoounts->sum('credits');
                $debs = $acoounts->sum('debs');
                $final_total = $acoounts->sum('final_total');


                $model->last_month_total = $last_month_total;
                $model->credits = $credits;
                $model->debs = $debs;
                $model->final_total = $final_total;

            }
        }
    }

