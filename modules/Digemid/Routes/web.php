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
                Route::prefix('digemid')->group(function () {
                    Route::get('/', 'DigemidController@index')->name('tenant.digemid.index');
                    Route::post('/update_exportable/{item?}', 'DigemidController@updateExportableItem');
                });
            });
        });
    }
