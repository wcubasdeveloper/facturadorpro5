<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) 
{
    Route::domain($hostname->fqdn)->group(function () {

        Route::get('client-errors/records', 'ClientErrorController@records');
         
        Route::prefix('transactions')->group(function () {
            Route::post('', 'TransactionController@store');
        });

        Route::middleware(['auth', 'locked.tenant'])->group(function() {

        });

    });
}

