<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'redirect.module', 'locked.tenant'])->group(function() {


            Route::prefix('finances')->group(function () {

                Route::get('global-payments', 'GlobalPaymentController@index')->name('tenant.finances.global_payments.index');
                Route::get('global-payments/pdf', 'GlobalPaymentController@pdf');
                Route::get('global-payments/excel', 'GlobalPaymentController@excel');
                Route::get('global-payments/filter', 'GlobalPaymentController@filter');
                Route::get('global-payments/records', 'GlobalPaymentController@records');

                /**
                 * finances/balance
                 * finances/balance/pdf
                 * finances/balance/excel
                 * finances/balance/filter
                 * finances/balance/records
                 */
                Route::get('balance', 'BalanceController@index')->name('tenant.finances.balance.index');
                Route::get('balance/pdf', 'BalanceController@pdf');
                Route::get('balance/excel', 'BalanceController@excel');
                Route::get('balance/filter', 'BalanceController@filter');
                Route::get('balance/records', 'BalanceController@records');

                Route::post('balance/bank_accounts', 'BalanceController@getBankAcounts');
                Route::post('balance/cash', 'BalanceController@getCashAcounts');
                Route::post('balance/transfer', 'BalanceController@makeTransfer');
                Route::get('payment-method-types', 'PaymentMethodTypeController@index')->name('tenant.finances.payment_method_types.index');
                Route::get('payment-method-types/pdf', 'PaymentMethodTypeController@pdf');
                Route::get('payment-method-types/excel', 'PaymentMethodTypeController@excel');
                Route::get('payment-method-types/filter', 'PaymentMethodTypeController@filter');
                Route::get('payment-method-types/records', 'PaymentMethodTypeController@records');

                /**
                 * finances/unpaid
                 * finances/unpaid/pdf
                 * finances/unpaid/filter
                 * finances/unpaid/records
                 * finances/unpaid/unpaidall
                 * finances/unpaid/report-payment-method-days
                 */
                Route::prefix('unpaid')->group(function () {
                    Route::get('', 'UnpaidController@index')->name('tenant.finances.unpaid.index');
                    // Route::post('', 'UnpaidController@unpaid');
                    Route::get('/filter', 'UnpaidController@filter');
                    // Route::post('/records', 'UnpaidController@records');
                    Route::get('/records', 'UnpaidController@records');
                    Route::get('/unpaidall', 'UnpaidController@unpaidall')->name('unpaidall');
                    Route::get('/report-payment-method-days', 'UnpaidController@reportPaymentMethodDays');
                    Route::get('/pdf', 'UnpaidController@pdf');
                });
                Route::post('payment-file/upload', 'PaymentFileController@uploadAttached');
                Route::get('payment-file/download-file/{filename}/{type}', 'PaymentFileController@download');


                /**
                 * finances/to-pay/
                 * finances/to-pay/filter
                 * finances/to-pay/records
                 * finances/to-pay/to-pay-all
                 * finances/to-pay/to-pay
                 * finances/to-pay/report-payment-method-days
                 * finances/to-pay/pdf
                 */
                Route::prefix('to-pay')->group(function () {
                    Route::get('', 'ToPayController@index')->name('tenant.finances.to_pay.index');
                    Route::get('/filter', 'ToPayController@filter');
                    Route::post('/records', 'ToPayController@records');
                    Route::get('/to-pay-all', 'ToPayController@toPayAll')->name('toPayAll');
                    Route::get('/to-pay', 'ToPayController@toPay');
                    Route::get('/report-payment-method-days', 'ToPayController@reportPaymentMethodDays');
                    Route::get('/pdf', 'ToPayController@pdf');
                });

                Route::prefix('income')->group(function () {

                    Route::get('', 'IncomeController@index')->name('tenant.finances.income.index');
                    Route::get('columns', 'IncomeController@columns');
                    Route::get('records', 'IncomeController@records');
                    Route::get('records/income-payments/{record}', 'IncomeController@recordsIncomePayments');
                    Route::get('create', 'IncomeController@create')->name('tenant.income.create');
                    Route::get('tables', 'IncomeController@tables');
                    Route::get('table/{table}', 'IncomeController@table');
                    Route::post('', 'IncomeController@store');
                    Route::get('record/{record}', 'IncomeController@record');
                    Route::get('voided/{record}', 'IncomeController@voided');

                });

                /**
                 * finances/movements
                 * finances/movements/pdf
                 * finances/movements/excel
                 * finances/movements/records
                 */
                Route::prefix('movements')->group(function () {

                    Route::get('', 'MovementController@index')->name('tenant.finances.movements.index');
                    Route::get('pdf', 'MovementController@pdf');
                    Route::post('pdf', 'MovementController@postPdf');
                    Route::get('excel', 'MovementController@excel');
                    //Route::post('excel', 'MovementController@excel');
                    //Route::post('excel', 'MovementController@excel');
                    Route::get('records', 'MovementController@records');
                });
                /**
                 * finances/transactions
                 * finances/transactions/pdf
                 * finances/transactions/excel
                 * finances/transactions/records
                 */
                Route::prefix('transactions')->group(function () {

                    Route::get('', 'MovementController@indexTransactions')->name('tenant.finances.transactions.index');
                    Route::get('pdf', 'MovementController@pdf');
                    Route::post('pdf', 'MovementController@postPdf');
                    Route::get('excel', 'MovementController@excel');
                    Route::post('excel', 'MovementController@excel');
                    Route::post('excel', 'MovementController@postExcel');
                    Route::get('records', 'MovementController@records');

                });
                /**
                 * finances/movements_cash
                 * finances/movements_cash/pdf
                 * finances/movements_cash/excel
                 * finances/movements_cash/records
                 */
                Route::prefix('movements_cash')->group(function () {

                    Route::get('', 'MovementController@index')->name('tenant.finances.movements_cash.index');
                    Route::get('pdf', 'MovementController@pdf');
                    Route::post('pdf', 'MovementController@postPdf');
                    Route::get('excel', 'MovementController@excel');
                    Route::post('excel', 'MovementController@excel');
                    Route::post('excel', 'MovementController@postExcel');
                    Route::get('records', 'MovementController@records');

                });

            });


            Route::prefix('income-types')->group(function () {

                Route::get('/records', 'IncomeTypeController@records');
                Route::get('/record/{id}', 'IncomeTypeController@record');
                Route::post('', 'IncomeTypeController@store');
                Route::delete('/{id}', 'IncomeTypeController@destroy');

            });

            Route::prefix('income-reasons')->group(function () {

                Route::get('/records', 'IncomeReasonController@records');
                Route::get('/record/{id}', 'IncomeReasonController@record');
                Route::post('', 'IncomeReasonController@store');
                Route::delete('/{id}', 'IncomeReasonController@destroy');

            });

        });
    });
}
