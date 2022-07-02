<?php

    use Illuminate\Support\Facades\Route;

    $hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

    if ($hostname) {
        Route::domain($hostname->fqdn)->group(function () {
            Route::middleware(['auth', 'redirect.module', 'locked.tenant'])
                ->prefix('documentary-procedure')
                ->group(function () {

                    Route::get('offices', 'DocumentaryOfficeController@index')->name('documentary.offices');
                    Route::post('offices/store', 'DocumentaryOfficeController@store');
                    Route::put('offices/{id}/update', 'DocumentaryOfficeController@update');
                    Route::delete('offices/{id}/delete', 'DocumentaryOfficeController@destroy');

                    Route::get('status', 'DocumentaryStatusController@index')->name('documentary.status');
                    Route::post('status/store', 'DocumentaryStatusController@store');
                    Route::put('status/{id}/update', 'DocumentaryStatusController@update');
                    Route::delete('status/{id}/delete', 'DocumentaryStatusController@destroy');


                    Route::get('processes', 'DocumentaryProcessController@index')->name('documentary.processes');
                    Route::post('processes/store', 'DocumentaryProcessController@store');
                    Route::put('processes/{id}/update', 'DocumentaryProcessController@update');
                    Route::delete('processes/{id}/delete', 'DocumentaryProcessController@destroy');

                    Route::get('documents', 'DocumentaryDocumentController@index')->name('documentary.documents');
                    Route::post('documents/store', 'DocumentaryDocumentController@store');
                    Route::put('documents/{id}/update', 'DocumentaryDocumentController@update');
                    Route::delete('documents/{id}/delete', 'DocumentaryDocumentController@destroy');

                    Route::get('actions', 'DocumentaryActionController@index')->name('documentary.actions');
                    Route::post('actions/store', 'DocumentaryActionController@store');
                    Route::put('actions/{id}/update', 'DocumentaryActionController@update');
                    Route::delete('actions/{id}/delete', 'DocumentaryActionController@destroy');

                    Route::get('requirements', 'DocumentaryRequirementsController@index')->name('documentary.requirements');
                    Route::post('requirements', 'DocumentaryRequirementsController@index');
                    Route::post('requirements/store', 'DocumentaryRequirementsController@store');
                    Route::put('requirements/update', 'DocumentaryRequirementsController@update');
                    Route::delete('requirements/{id}/delete', 'DocumentaryRequirementsController@destroy');

                    Route::get('files', 'DocumentaryFileController@index')->name('documentary.files');
                    Route::get('files/export/excel', 'DocumentaryFileController@excel');
                    Route::get('files/export/pdf', 'DocumentaryFileController@pdf');
                    Route::post('files/store', 'DocumentaryFileController@store');
                    Route::post('files/{id}/update', 'DocumentaryFileController@update');
                    Route::delete('files/{id}/delete', 'DocumentaryFileController@destroy');
                    Route::get('files/tables', 'DocumentaryFileController@tables');
                    Route::get('files/create', 'DocumentaryFileController@create');
                    Route::get('files/document-number', 'DocumentaryFileController@getDocumentNumber');
                    Route::post('files/{id}/add-office', 'DocumentaryFileController@addOffice');
                    Route::post('files/next', 'DocumentaryFileController@nextStep');
                    Route::post('files/back', 'DocumentaryFileController@backStep');
                    Route::get('files/download/{id}', 'DocumentaryFilesArchivesController@download')
                        ->name('documentaryprocedure.download.file');
                    route::get('file/remove/{id}', 'DocumentaryFilesArchivesController@destroy');
                    route::post('file/reload/{id?}', 'DocumentaryFileController@getData');

                    route::post('files/addStage', 'DocumentaryFileController@addStage');
                    route::post('files/addStatus', 'DocumentaryFileController@addStatus');
                    Route::post('files/calculateDays', 'DocumentaryFileController@calculateEndDays');

                    /**
                    documentary-procedure/files_simplify/create
                    documentary-procedure/files_simplify/new
                    documentary-procedure/files_simplify/edit/{id?
                    documentary-procedure/files_simplify/ask/{id?}
                    documentary-procedure/files_simplify/destroy/{id?}
                    documentary-procedure/files_simplify/archive/{id?}
                    documentary-procedure/files_simplify/reactive/{id?}
                    documentary-procedure/files_simplify/tables
                    documentary-procedure/files_simplify/{id}/update
                    documentary-procedure/files_simplify/document-number
                    documentary-procedure/files_simplify/store
                    documentary-procedure/files_simplify/
                    documentary-procedure/files_simplify/removeStage/{id}
                    documentary-procedure/files_simplify/updateStage/{id}
                    documentary-procedure/files_simplify/export_current/{id}
                    documentary-procedure/files_simplify/export/excel
                    documentary-procedure/files_simplify/export/pdf
                    documentary-procedure/files_simplify/upload/{id}/update
                    documentary-procedure/files_simplify/upload/store
                    documentary-procedure/files_simplify/search/customers
                    */
                    Route::
                        prefix('files_simplify')
                        ->group(function () {
                            Route::get('/create', 'DocumentaryFileController@index_simplify_new');
                            Route::get('/new', 'DocumentaryFileController@index_simplify_new');
                            Route::get('/edit/{id?}', 'DocumentaryFileController@index_simplify_new');
                            Route::post('/ask/{id?}', 'DocumentaryFileController@getDocumentary');
                            Route::post('/destroy/{id?}', 'DocumentaryFileController@destroy');
                            Route::post('/complete/{id?}', 'DocumentaryFileController@complete');
                            Route::post('/archive/{id?}', 'DocumentaryFileController@archive');
                            Route::post('/reactive/{id?}', 'DocumentaryFileController@reactive');
                            Route::get('/tables', 'DocumentaryFileController@tables');
                            Route::post('/{id}/update', 'DocumentaryFileController@store_simplify');
                            Route::get('/document-number', 'DocumentaryFileController@getDocumentNumber');
                            Route::post('/store', 'DocumentaryFileController@store_simplify');
                            Route::get('', 'DocumentaryFileController@index_simplify')->name('documentary.files_simplify');
                            Route::post('/removeStage/{id}', 'DocumentaryFileController@removeGuide');
                            Route::post('/updateStage/{id}', 'DocumentaryFileController@updateStatus');
                            Route::get('/export_current/{id}', 'DocumentaryFileController@pdfIndividual');
                            Route::get('/export/excel', 'DocumentaryFileController@excel');
                            Route::get('/export/pdf', 'DocumentaryFileController@pdf');
                            Route::post('/upload/{id}/update', 'DocumentaryFileController@uploadFile');
                            Route::post('/upload/store', 'DocumentaryFileController@uploadFile');
                            Route::get('/search/customers/{id?}', 'DocumentaryFileController@searchCustomerById');
                        });



                    Route::
                    prefix('stadistic')
                        ->group(function () {
                            Route::get('/', 'DocumentaryStatisticController@index')
                                ->name('documentary.stadistic');
                            Route::post('/', 'DocumentaryStatisticController@records');

                            Route::get('/{type}', 'DocumentaryStatisticController@export');
                        });




                });
        });
    }
