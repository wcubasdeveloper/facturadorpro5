<?php

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($hostname) {
    Route::domain($hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function() {


            Route::get('categories', 'CategoryController@index')->name('tenant.categories.index')->middleware('redirect.level');
            Route::get('categories/records', 'CategoryController@records');
            Route::get('categories/columns', 'CategoryController@columns');
            Route::get('categories/record/{category}', 'CategoryController@record');
            Route::post('categories', 'CategoryController@store');
            Route::delete('categories/{category}', 'CategoryController@destroy');

            Route::get('brands', 'BrandController@index')->name('tenant.brands.index')->middleware('redirect.level');
            Route::get('brands/records', 'BrandController@records');
            Route::get('brands/record/{brand}', 'BrandController@record');
            Route::post('brands', 'BrandController@store');
            Route::get('brands/columns', 'BrandController@columns');
            Route::delete('brands/{brand}', 'BrandController@destroy');



            Route::prefix('zones')->group(function () {

                Route::get('', 'ZoneController@index')->name('tenant.zone.index');
                Route::post('', 'ZoneController@store');
                Route::get('/records', 'ZoneController@records');
                Route::get('/record/{brand}', 'ZoneController@record');
                Route::get('/columns', 'ZoneController@columns');
                Route::delete('/{brand}', 'ZoneController@destroy');
            });


            Route::get('incentives', 'IncentiveController@index')->name('tenant.incentives.index')->middleware('redirect.level');
            Route::get('incentives/records', 'IncentiveController@records');
            Route::get('incentives/record/{incentive}', 'IncentiveController@record');
            Route::post('incentives', 'IncentiveController@store');
            Route::get('incentives/columns', 'IncentiveController@columns');
            Route::delete('incentives/{incentive}', 'IncentiveController@destroy');

            Route::get('items/barcode/{item}', 'ItemController@generateBarcode');

            Route::post('items/import/item-price-lists', 'ItemController@importItemPriceLists');
            Route::post('items/import/item-with-extra-data', 'ItemController@importItemWithExtraData');

            //history
            Route::get('items/data-history/{item}', 'ItemController@getDataHistory');
            Route::get('items/available-series/records', 'ItemController@availableSeriesRecords');
            Route::get('items/history-sales/records', 'ItemController@itemHistorySales');
            Route::get('items/history-purchases/records', 'ItemController@itemHistoryPurchases');
            Route::get('items/last-sale', 'ItemController@itemtLastSale');

            //history

            Route::prefix('item-lots')->group(function () {

                Route::get('', 'ItemLotController@index')->name('tenant.item-lots.index');
                Route::get('/records', 'ItemLotController@records');
                Route::get('/record/{record}', 'ItemLotController@record');
                Route::post('', 'ItemLotController@store');
                Route::get('/columns', 'ItemLotController@columns');
                Route::get('/export', 'ItemLotController@export');

            });

            Route::post('items/import/item-sets', 'ItemSetController@importItemSets');
            Route::post('items/import/item-sets-individual', 'ItemSetController@importItemSetsIndividual');


            Route::prefix('web-platforms')->group(function () {

                Route::get('', 'WebPlatformController@index');
                Route::get('/records', 'WebPlatformController@records');
                Route::get('/record/{brand}', 'WebPlatformController@record');
                Route::post('', 'WebPlatformController@store');
                Route::delete('/{record}', 'WebPlatformController@destroy');

            });

            Route::post('items/import/items-update-prices', 'ItemController@importItemUpdatePrices');

        });
    });
}
