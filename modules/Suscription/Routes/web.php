<?php


    use Illuminate\Support\Facades\Route;

    $current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

    if ($current_hostname) {
        Route::domain($current_hostname->fqdn)
            ->middleware(['redirect.level'])
            ->group(function () {
            Route::middleware(['auth', 'locked.tenant'])
                ->prefix('suscription')
                ->group(function () {
                    /**
                     * suscription/client
                     */
                    Route::prefix('client')->group(function () {
                        Route::get('/', 'ClientSuscriptionController@index') ->name('tenant.suscription.client.index');
                        Route::get('/childrens', 'ClientSuscriptionController@indexChildren')->name('tenant.suscription.client_children.index');
                        Route::post('/', 'ClientSuscriptionController@store');

                        Route::get('/columns', 'ClientSuscriptionController@Columns');
                        Route::post('/records', 'ClientSuscriptionController@Records');
                        Route::post('/tables', 'ClientSuscriptionController@Tables');
                        Route::post('/record', 'ClientSuscriptionController@Record');


                    });
                    /**
                     * suscription/service
                     */
                    Route::prefix('service')->group(function () {
                        Route::get('/', 'ServiceSuscriptionController@index')
                            ->name('tenant.suscription.service.index')
                            ->middleware(['redirect.level']);
                        /*

                        Route::get('/columns', 'ServiceSuscriptionController@Columns');
                        Route::post('/records', 'ServiceSuscriptionController@Records');
                        Route::post('/tables', 'ServiceSuscriptionController@Tables');
                        Route::post('/record', 'ServiceSuscriptionController@Record');
                        */
                    });
                    // items/export/barcode/last

                    /**
                     * suscription/plans
                     */
                    Route::prefix('plans')->group(function () {
                        Route::get('/', 'PlansSuscriptionController@index')
                            ->name('tenant.suscription.plans.index')
                            ->middleware(['redirect.level']);
                        Route::post('/', 'PlansSuscriptionController@store');

                        Route::get('/columns', 'PlansSuscriptionController@Columns');
                        Route::post('/records', 'PlansSuscriptionController@Records');
                        Route::post('/tables', 'PlansSuscriptionController@Tables');
                        Route::post('/record', 'PlansSuscriptionController@Record');

                        Route::delete('/{id}', 'PlansSuscriptionController@destroy');

                    });

                    /**
                     * suscription/payments
                     */
                    Route::prefix('payments')->group(function () {

                        /*
                        Route::get('/', 'SuscriptionController@payments_index')
                            ->name('tenant.suscription.payments.index')
                            ->middleware(['redirect.level']);
                        */

                        Route::get('/', 'PaymentsSuscriptionController@index')
                            ->name('tenant.suscription.payments.index')
                            ->middleware(['redirect.level']);
                        Route::post('/', 'PaymentsSuscriptionController@store');

                        Route::get('/columns', 'PaymentsSuscriptionController@Columns');
                        Route::post('/records', 'PaymentsSuscriptionController@Records');
                        Route::post('/tables', 'PaymentsSuscriptionController@Tables');
                        Route::post('/record', 'PaymentsSuscriptionController@Record');
                        Route::post('/search/customers', 'PaymentsSuscriptionController@searchCustomer');

                    });
                    /**
                     * suscription/payment_receipt
                     */
                    Route::prefix('payment_receipt')->group(function () {
                        Route::get('/', 'PaymentReceiptSuscriptionController@index')
                            ->name('tenant.suscription.payment_receipt.index');

                    });


                    Route::post('CommonData','SuscriptionController@Tables');
                });
        })
        ;
    }
