<?php


    use Illuminate\Support\Facades\Route;

    $current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

    if ($current_hostname) {
        Route::domain($current_hostname->fqdn)
            ->middleware(['redirect.level'])
            ->group(function () {
                Route::middleware(['auth', 'locked.tenant'])
                    ->prefix('full_suscription')
                    ->group(function () {
                        /**
                         * full_suscription/client
                         */
                        Route::prefix('client')->group(function () {
                            Route::get('/', 'ClientFullSuscriptionController@index')->name('tenant.fullsuscription.client.index');
                            Route::get('/childrens', 'ClientFullSuscriptionController@indexChildren')->name('tenant.fullsuscription.client_children.index');
                            Route::post('/', 'ClientFullSuscriptionController@store');

                            Route::get('/columns', 'ClientFullSuscriptionController@Columns');
                            Route::post('/records', 'ClientFullSuscriptionController@Records');
                            Route::post('/tables', 'ClientFullSuscriptionController@Tables');
                            Route::post('/record', 'ClientFullSuscriptionController@Record');
                            Route::post('/record/server', 'ClientFullSuscriptionController@RecordServer');


                        });
                        /**
                         * full_suscription/service
                         */
                        Route::prefix('service')->group(function () {
                            Route::get('/', 'ServiceFullSuscriptionController@index')
                                ->name('tenant.fullsuscription.service.index')
                                ->middleware(['redirect.level']);
                            /*

                            Route::get('/columns', 'ServiceFullSuscriptionController@Columns');
                            Route::post('/records', 'ServiceFullSuscriptionController@Records');
                            Route::post('/tables', 'ServiceFullSuscriptionController@Tables');
                            Route::post('/record', 'ServiceFullSuscriptionController@Record');
                            */
                        });
                        // items/export/barcode/last

                        /**
                         * full_suscription/plans
                         */
                        Route::prefix('plans')->group(function () {
                            Route::get('/', 'PlansFullSuscriptionController@index')
                                ->name('tenant.fullsuscription.plans.index')
                                ->middleware(['redirect.level']);
                            Route::post('/', 'PlansFullSuscriptionController@store');

                            Route::get('/columns', 'PlansFullSuscriptionController@Columns');
                            Route::post('/records', 'PlansFullSuscriptionController@Records');
                            Route::post('/tables', 'PlansFullSuscriptionController@Tables');
                            Route::post('/record', 'PlansFullSuscriptionController@Record');

                            Route::delete('/{id}', 'PlansFullSuscriptionController@destroy');

                        });

                        /**
                         * full_suscription/payments
                         */
                        Route::prefix('payments')->group(function () {

                            Route::get('/', 'PaymentsFullSuscriptionController@index')
                                ->name('tenant.fullsuscription.payments.index')
                                ->middleware(['redirect.level']);
                            Route::post('/', 'PaymentsFullSuscriptionController@store');

                            Route::get('/columns', 'PaymentsFullSuscriptionController@Columns');
                            Route::post('/records', 'PaymentsFullSuscriptionController@Records');
                            Route::post('/tables', 'PaymentsFullSuscriptionController@Tables');
                            Route::post('/record', 'PaymentsFullSuscriptionController@Record');
                            Route::post('/search/customers', 'PaymentsFullSuscriptionController@searchCustomer');

                        });
                        /**
                         * full_suscription/payment_receipt
                         */
                        Route::prefix('payment_receipt')->group(function () {
                            Route::get('/', 'PaymentReceiptFullSuscriptionController@index')
                                ->name('tenant.fullsuscription.payment_receipt.index');

                        });


                        Route::post('CommonData', 'FullSuscriptionController@Tables');
                    });
            });
    }
