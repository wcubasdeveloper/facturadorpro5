<?php

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'redirect.module', 'locked.tenant'])->group(function () {

            // Route::redirect('/', '/dashboard');
            /**
             * expenses/
             * expenses/columns
             * expenses/records
             * expenses/records/expense-payments/{expense}
             * expenses/create/{id?}
             * expenses/tables
             * expenses/table/{table}
             * expenses/record/{expense}
             * expenses/record}/voided
             * expenses/report/excel
             */

            Route::prefix('expenses')->group(function () {

                Route::get('', 'ExpenseController@index')->name('tenant.expenses.index');
                Route::get('columns', 'ExpenseController@columns');
                Route::get('records', 'ExpenseController@records');
                Route::get('records/expense-payments/{expense}', 'ExpenseController@recordsExpensePayments');
                Route::get('create/{id?}', 'ExpenseController@create')->name('tenant.expenses.create');
                Route::get('tables', 'ExpenseController@tables');
                Route::get('table/{table}', 'ExpenseController@table');
                Route::post('', 'ExpenseController@store');
                Route::get('record/{expense}', 'ExpenseController@record');
                Route::get('{record}/voided', 'ExpenseController@voided');
                Route::get('report/excel', 'ExpenseController@excel');

            });
            /**
             * Creditos bancarios
             * bank_loan/
             * bank_loan/columns
             * bank_loan/records
             * bank_loan/records/expense-payments/{expense}
             * bank_loan/create/{id?}
             * bank_loan/tables
             * bank_loan/table/{table}
             * bank_loan/record/{expense}
             * bank_loan/record}/voided
             * bank_loan/report/excel
             */
            Route::prefix('bank_loan')->group(function () {
                Route::get('', 'BankLoanController@index')->name('tenant.bank_loan.index');
                Route::get('columns', 'BankLoanController@columns');
                Route::get('records', 'BankLoanController@records');
                Route::get('records/expense-payments/{expense}', 'BankLoanController@recordsExpensePayments');
                Route::get('create/{id?}', 'BankLoanController@create')->name('tenant.bank_loan.create');
                Route::get('tables', 'BankLoanController@tables');
                Route::get('table/{table}', 'BankLoanController@table');
                Route::post('', 'BankLoanController@store');
                Route::get('record/{expense}', 'BankLoanController@record');
                Route::get('{record}/voided', 'BankLoanController@voided');
                Route::get('report/excel', 'BankLoanController@excel');
            });
            /**
             * Creditos bancarios
             * loan-payments/records/{expense_id}
             * loan-payments/expense/{expense_id}
             * loan-payments/tables
             * loan-payments
             * loan-payments/{expense_payment}
             */
            Route::prefix('loan-payments')->group(function () {
                Route::get('/records/{expense_id}', 'BankLoanPaymentController@records');
                Route::get('/expense/{expense_id}', 'BankLoanPaymentController@expense');
                Route::get('/tables', 'BankLoanPaymentController@tables');
                Route::post('', 'BankLoanPaymentController@store');
                Route::delete('/{expense_payment}', 'BankLoanPaymentController@destroy');
            });

            Route::prefix('expense-payments')->group(function () {

                Route::get('/records/{expense_id}', 'ExpensePaymentController@records');
                Route::get('/expense/{expense_id}', 'ExpensePaymentController@expense');
                Route::get('/tables', 'ExpensePaymentController@tables');
                Route::post('', 'ExpensePaymentController@store');
                Route::delete('/{expense_payment}', 'ExpensePaymentController@destroy');

            });

            Route::prefix('expense-types')->group(function () {

                Route::get('/records', 'ExpenseTypeController@records');
                Route::get('/record/{id}', 'ExpenseTypeController@record');
                Route::post('', 'ExpenseTypeController@store');
                Route::delete('/{id}', 'ExpenseTypeController@destroy');

            });

            Route::prefix('expense-reasons')->group(function () {

                Route::get('/records', 'ExpenseReasonController@records');
                Route::get('/record/{id}', 'ExpenseReasonController@record');
                Route::post('', 'ExpenseReasonController@store');
                Route::delete('/{id}', 'ExpenseReasonController@destroy');

            });

            Route::prefix('expense-method-types')->group(function () {

                Route::get('/records', 'ExpenseMethodTypeController@records');
                Route::get('/record/{id}', 'ExpenseMethodTypeController@record');
                Route::post('', 'ExpenseMethodTypeController@store');
                Route::delete('/{id}', 'ExpenseMethodTypeController@destroy');

            });

        });
    });
}
