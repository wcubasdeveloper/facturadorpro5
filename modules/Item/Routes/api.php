<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if ($hostname) 
{
    Route::domain($hostname->fqdn)->group(function () {

        Route::middleware(['auth:api', 'locked.tenant'])->group(function () {

            Route::get('categories-records', 'Api\CategoryController@records');
            Route::get('brands-records', 'Api\BrandController@records');

            Route::prefix('items')->group(function () {
                
                Route::post('update', 'Api\ItemController@update');

            });

        }); 
    });
} 
