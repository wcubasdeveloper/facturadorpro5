<?php

    use Illuminate\Support\Facades\Route;

    $current_hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);

if($current_hostname) {
    Route::domain($current_hostname->fqdn)->group(function () {
        Route::middleware(['auth', 'locked.tenant'])->group(function () {

            // Route::redirect('/', '/dashboard');

            Route::prefix('purchases')->group(function () {

                Route::post('import-series', 'PurchaseController@importSeries');
            });


            Route::prefix('purchase-quotations')->group(function () {

                Route::get('', 'PurchaseQuotationController@index')->name('tenant.purchase-quotations.index');
                Route::get('columns', 'PurchaseQuotationController@columns');
                Route::get('records', 'PurchaseQuotationController@records');
                Route::get('create/{id?}', 'PurchaseQuotationController@create')->name('tenant.purchase-quotations.create');
                Route::get('tables', 'PurchaseQuotationController@tables');
                Route::get('table/{table}', 'PurchaseQuotationController@table');
                Route::post('', 'PurchaseQuotationController@store');
                Route::get('record/{expense}', 'PurchaseQuotationController@record');
                Route::get('item/tables', 'PurchaseQuotationController@item_tables');
                Route::get('download/{external_id}/{format?}', 'PurchaseQuotationController@download');
                Route::get('print/{external_id}/{format?}', 'PurchaseQuotationController@toPrint');
                Route::get('search-items', 'PurchaseQuotationController@searchItems');
                Route::get('search/item/{item}', 'PurchaseQuotationController@searchItemById');

            });

            /**
            purchase-orders/
            purchase-orders/columns
            purchase-orders/records
            purchase-orders/create/{id?}
            purchase-orders/generate/{id}
            purchase-orders/tables
            purchase-orders/table/{table}
            purchase-orders/
            purchase-orders/record/{expense}
            purchase-orders/item/tables
            purchase-orders/download/{external_id}/{format?}
            purchase-orders/print/{external_id}/{format?}
            purchase-orders/upload
            purchase-orders/anular/{id}
            purchase-orders/download-attached/{external_id}
            purchase-orders/sale-opportunity/{id}
            purchase-orders/email
            purchase-orders/search/item/{item}
            purchase-orders/search-items
             */
            Route::prefix('purchase-orders')->group(function () {

                Route::get('', 'PurchaseOrderController@index')->name('tenant.purchase-orders.index')->middleware('redirect.level');
                Route::get('columns', 'PurchaseOrderController@columns');
                Route::get('records', 'PurchaseOrderController@records');
                Route::get('create/{id?}', 'PurchaseOrderController@create')->name('tenant.purchase-orders.create');
                Route::get('generate/{id}', 'PurchaseOrderController@generate')->name('tenant.purchase-orders.generate');
                Route::get('tables', 'PurchaseOrderController@tables');
                Route::get('table/{table}', 'PurchaseOrderController@table');
                Route::post('', 'PurchaseOrderController@store');
                Route::get('record/{expense}', 'PurchaseOrderController@record');
                Route::get('item/tables', 'PurchaseOrderController@item_tables');
                Route::get('download/{external_id}/{format?}', 'PurchaseOrderController@download');
                Route::get('print/{external_id}/{format?}', 'PurchaseOrderController@toPrint');
                Route::post('upload', 'PurchaseOrderController@uploadAttached');
                Route::get('anular/{id}', 'PurchaseOrderController@anular');

                Route::get('download-attached/{external_id}', 'PurchaseOrderController@downloadAttached');
                Route::get('sale-opportunity/{id}', 'PurchaseOrderController@generateFromSaleOpportunity');
                Route::post('email', 'PurchaseOrderController@email');
                Route::get('search-items', 'PurchaseOrderController@searchItems');
                Route::get('search/item/{item}', 'PurchaseOrderController@searchItemById');

            });

            Route::prefix('purchase-payments')->group(function () {

                Route::get('/records/{purchase_id}', 'PurchasePaymentController@records');
                Route::get('/purchase/{purchase_id}', 'PurchasePaymentController@purchase');
                Route::get('/tables', 'PurchasePaymentController@tables');
                Route::post('', 'PurchasePaymentController@store');
                Route::delete('/{purchase_payment}', 'PurchasePaymentController@destroy');

            });


            Route::prefix('fixed-asset')->group(function () {

                Route::get('items', 'FixedAssetItemController@index')->name('tenant.fixed_asset_items.index');
                Route::get('items/columns', 'FixedAssetItemController@columns');
                Route::get('items/records', 'FixedAssetItemController@records');
                Route::get('items/create/{id?}', 'FixedAssetItemController@create')->name('tenant.fixed_asset_items.create');
                Route::get('items/tables', 'FixedAssetItemController@tables');
                Route::get('items/table/{table}', 'FixedAssetItemController@table');
                Route::post('items/', 'FixedAssetItemController@store');
                Route::get('items/record/{item}', 'FixedAssetItemController@record');
                Route::get('items/item/tables', 'FixedAssetItemController@item_tables');
                Route::delete('/items/{item}', 'FixedAssetItemController@destroy');

                Route::get('purchases', 'FixedAssetPurchaseController@index')->name('tenant.fixed_asset_purchases.index');
                Route::get('purchases/columns', 'FixedAssetPurchaseController@columns');
                Route::get('purchases/records', 'FixedAssetPurchaseController@records');
                Route::get('purchases/create/{id?}', 'FixedAssetPurchaseController@create')->name('tenant.fixed_asset_purchases.create');
                Route::get('purchases/tables', 'FixedAssetPurchaseController@tables');
                Route::get('purchases/table/{table}', 'FixedAssetPurchaseController@table');
                Route::post('purchases', 'FixedAssetPurchaseController@store');
                Route::get('purchases/record/{document}', 'FixedAssetPurchaseController@record');
                Route::get('purchases/voided/{id}', 'FixedAssetPurchaseController@voided');
                Route::delete('purchases/delete/{id}', 'FixedAssetPurchaseController@delete');
                Route::get('purchases/item/tables', 'FixedAssetPurchaseController@item_tables');
            });

        });
    });
}
