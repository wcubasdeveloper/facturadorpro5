<?php

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    |
    | Here is where you can register web routes for your application. These
    | routes are loaded by the RouteServiceProvider within a group which
    | contains the "web" middleware group. Now create something great!
    |
    */

    use Illuminate\Support\Facades\Route;

    $current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

    if ($current_hostname) {
        Route::domain($current_hostname->fqdn)->group(function () {
            Route::middleware(['auth', 'locked.tenant'])->group(function () {

                /**
                 * item-production
                 * item-production/columns,
                 * item-production/records,
                 * item-production/tables,
                 * item-production/record/{item},
                 * item-production/{item},
                 * item-production/item-unit-type/{item},
                 * item-production/import,
                 * item-production/upload,
                 * item-production/visible_store,
                 * item-production/item/tables,
                 */
                Route::prefix('item-production')->group(function () {

                    // @todo Pasar al modulo de produccion
                    Route::get('', 'ItemProductionController@index')->name('tenant.item_production.index'); // ->middleware('redirect.level');
                    Route::post('', 'ItemProductionController@store');
                    Route::get('/columns', 'ItemProductionController@columns');
                    Route::get('/records', 'ItemProductionController@records');
                    Route::get('/tables', 'ItemProductionController@tables');
                    Route::get('/record/{item}', 'ItemProductionController@record');
                    Route::delete('/{item}', 'ItemProductionController@destroy');
                    Route::delete('/item-unit-type/{item}', 'ItemProductionController@destroyItemUnitType');
                    Route::post('/import', 'ItemProductionController@import');
                    Route::post('/upload', 'ItemProductionController@upload');
                    Route::post('/visible_store', 'ItemProductionController@visibleStore');
                    Route::get('/item/tables', 'ItemProductionController@item_tables');
                });

                Route::prefix('mill-production')->group(function () {

                    // modulo de insumos
                    Route::get('', 'MillController@index')->name('tenant.mill_production.index'); // ->middleware('redirect.level');
                    Route::get('/create/{id?}', 'MillController@create');
                    Route::post('/create', 'MillController@create');
                    Route::post('', 'MillController@store');
                    Route::get('/columns', 'MillController@columns');
                    Route::get('/records', 'MillController@records');
                    Route::get('/tables', 'MillController@tables');
                    Route::get('/record/{item}', 'MillController@record');
                    Route::delete('/{item}', 'MillController@destroy');
                    Route::delete('/item-unit-type/{item}', 'MillController@destroyItemUnitType');
                    Route::post('/import', 'MillController@import');
                    Route::post('/upload', 'MillController@upload');
                    Route::post('/visible_store', 'MillController@visibleStore');
                    Route::get('/item/tables', 'MillController@item_tables');
                    Route::get('/excel', 'MillController@excel');
                    Route::get('/pdf', 'MillController@pdf');
                });
                Route::prefix('machine-type-production')->group(function () {
                    Route::get('/create/{id?}', 'MachineController@createType');
                    Route::post('/create', 'MachineController@saveType');
                    Route::get('/record/{item}', 'MachineController@recordType');
                    Route::get('/records', 'MachineController@recordsType');
                    Route::get('', 'MachineController@indexType')->name('tenant.machine_type_production.index'); // ->middleware('redirect.level');
                    Route::get('/columns', 'MachineController@columnsType');

                });

                Route::prefix('machine-production')->group(function () {


                        Route::get('', 'MachineController@index')->name('tenant.machine_production.index'); // ->middleware('redirect.level');
                        Route::get('/create/{id?}', 'MachineController@create');

                        Route::post('/create', 'MachineController@create');
                        Route::post('', 'MachineController@store');
                        Route::get('/columns', 'MachineController@columns');
                        Route::get('/records', 'MachineController@records');
                        Route::get('/tables', 'MachineController@tables');
                        Route::get('/record/{item}', 'MachineController@record');
                        Route::delete('/{item}', 'MachineController@destroy');
                        Route::delete('/item-unit-type/{item}', 'MachineController@destroyItemUnitType');
                        Route::post('/import', 'MachineController@import');
                        Route::post('/upload', 'MachineController@upload');
                        Route::post('/visible_store', 'MachineController@visibleStore');
                        Route::get('/item/tables', 'MachineController@item_tables');
                    });

                Route::prefix('production')->group(function () {
                    Route::get('', 'ProductionController@index')->name('tenant.production.index'); // ->middleware('redirect.level');
                    Route::get('create', 'ProductionController@create')->name('tenant.production.new');
                    Route::post('create', 'ProductionController@store');
                    Route::post('search_items', 'ProductionController@searchItems');
                    Route::get('/records', 'ProductionController@records');
                    Route::get('/tables', 'ProductionController@tables');
                    Route::get('/excel', 'ProductionController@excel');
                    Route::get('/excel2', 'ProductionController@excel2');
                    Route::get('/pdf', 'ProductionController@pdf');

                });
                Route::prefix('packaging')->group(function () {
                    Route::get('', 'PackagingController@index')->name('tenant.packaging.index'); // ->middleware('redirect.level');
                    Route::get('create', 'PackagingController@create')->name('tenant.packaging.new');
                    Route::post('create', 'PackagingController@store');
                    Route::post('search_items', 'PackagingController@searchItems');
                    Route::get('/records', 'PackagingController@records');
                    Route::get('/tables', 'PackagingController@tables');
                    Route::get('/excel', 'PackagingController@excel');
                    Route::get('/pdf', 'PackagingController@pdf');

                });

                
                Route::prefix('workers')->group(function () {
                 
                    Route::get('columns', 'WorkerController@columns');
                    Route::get('tables', 'WorkerController@tables');
                    Route::get('', 'WorkerController@index')->name('tenant.workers.index');
                    Route::get('records', 'WorkerController@records');
                    Route::get('record/{worker}', 'WorkerController@record');
                    Route::post('', 'WorkerController@store');
                    Route::delete('{person}', 'WorkerController@destroy');
                    
                });

            });
        });
    }


