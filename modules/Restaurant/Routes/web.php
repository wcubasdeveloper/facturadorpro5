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

Route::prefix('restaurant')->group(function() {
    // para configuracion de productos a mostrar
    Route::get('/list/items', 'RestaurantController@list_items')->name('tenant.restaurant.list_items');
    Route::post('items/visible', 'RestaurantController@is_visible');
    Route::get('item_partial/{id}', 'RestaurantController@partialItem')->name('restaurant.item_partial');
    Route::get('item/{id}/{promotion_id?}', 'RestaurantController@item')->name('restaurant.item');
    Route::get('cart', 'RestaurantController@detailCart')->name('restaurant.detail.cart');
    Route::post('payment_cash', 'RestaurantController@paymentCash')->name('restaurant.payment.cash');

    // vista de configuracion general
    Route::get('configuration', 'RestaurantConfigurationController@configuration')->name('tenant.restaurant.configuration');
    Route::get('configuration/record', 'RestaurantConfigurationController@record')->name('tenant.restaurant.configuration.record');
    Route::post('configuration', 'RestaurantConfigurationController@setConfiguration')->name('tenant.restaurant.configuration.set');
    Route::get('get-users', 'RestaurantConfigurationController@getUsers')->name('tenant.restaurant.users.get');
    Route::get('get-roles', 'RestaurantConfigurationController@getRoles')->name('tenant.restaurant.roles.get');
    Route::post('user/set-role', 'RestaurantConfigurationController@setRole')->name('tenant.restaurant.role.set');



    //Promotion
    Route::prefix('promotions')->group(function() {

        Route::get('', 'PromotionController@index')->name('tenant.restaurant.promotion.index');
        Route::get('columns', 'PromotionController@columns');
        Route::get('tables', 'PromotionController@tables');
        Route::get('records', 'PromotionController@records');
        Route::get('record/{tag}', 'PromotionController@record');
        Route::post('', 'PromotionController@store');
        Route::delete('{promotion}', 'PromotionController@destroy');
        Route::post('upload', 'PromotionController@upload');

    });

    //Orders
    Route::prefix('orders')->group(function() {

        Route::get('', 'OrderController@index')->name('tenant.restaurant.order.index');
        Route::get('columns', 'OrderController@columns');
        Route::get('records', 'OrderController@records');
        Route::get('record/{order}', 'OrderController@record');
        Route::get('pdf/{id}', 'OrderController@pdf');

        //warehouse
        Route::post('warehouse', 'OrderController@searchWarehouse');
        Route::get('tables', 'OrderController@tables');
        Route::get('tables/item/{internal_id}', 'OrderController@item');

    });

    //Cash
    Route::prefix('cash')->group(function() {

        Route::get('', 'CashController@index')->name('tenant.restaurant.cash.index');
        Route::get('/pos', 'CashController@posFilter')->name('tenant.restaurant.cash.filter-pos');
        Route::get('records', 'CashController@records');
        Route::get('report', 'CashController@report_general');
        Route::get('columns', 'CashController@columns');

        Route::get('tables', 'CashController@tables');
        Route::get('opening_cash', 'CashController@opening_cash');
        Route::get('opening_cash_check/{user_id}', 'CashController@opening_cash_check');
        Route::post('cash', 'Tenant\CashController@store');
        Route::post('cash_document', 'CashController@cash_document');
        Route::get('close/{cash}', 'CashController@close');
        Route::get('report/{cash}', 'CashController@report');
        Route::get('report', 'CashController@report_general');
        Route::get('record/{cash}', 'CashController@record');
        Route::delete('{cash}', 'CashController@destroy');
        Route::get('item/tables', 'CashController@item_tables');
        Route::get('search/customers', 'CashController@searchCustomers');
        Route::get('search/customer/{id}', 'CashController@searchCustomerById');
        Route::get('report/products/{cash}', 'CashController@report_products');
        Route::get('report/products-excel/{cash}', 'CashController@report_products_excel');


    });



});

// ruta publica
Route::middleware(['locked.tenant'])->group(function() {
    // restaurant
    Route::get('/menu/{name?}', 'RestaurantController@menu')->name('tenant.restaurant.menu');


});
