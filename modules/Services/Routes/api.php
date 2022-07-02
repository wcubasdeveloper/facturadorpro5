<?php

use Illuminate\Support\Facades\Route;

$current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth:api'])->group(function () {
            Route::prefix('services')->group(function () {
                Route::get('exchange/{date}', 'ServiceController@exchange');
            });
        });
    });
}
