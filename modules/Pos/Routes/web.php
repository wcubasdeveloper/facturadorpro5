<?php


$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {

            Route::prefix('pos')->group(function() {

                Route::get('history-sales/records', 'HistoryController@recordsSales');
                Route::get('history-purchases/records', 'HistoryController@recordsPurchases');

            });

            Route::prefix('cash')->group(function() {

                /*
                 * cash/report-a4/{cash}
                 * cash/report-ticket/{cash}
                 * cash/report-excel/{cash}
                 * cash/email
                 */
                Route::get('report-a4/{cash}', 'CashController@reportA4');
                Route::get('report-ticket/{cash}/{format?}', 'CashController@reportTicket');
                Route::get('report-excel/{cash}', 'CashController@reportExcel');
                Route::post('email', 'CashController@email');

                Route::get('report-cash-income-egress/{cash}', 'CashController@reportCashIncomeEgress');
                

            });
        });
    });
}
