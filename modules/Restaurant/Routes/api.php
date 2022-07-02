<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$hostname = app(Hyn\Tenancy\Contracts\CurrentHostname::class);
if ($hostname) {
    Route::domain($hostname->fqdn)->group(function() {

        Route::middleware(['auth:api', 'locked.tenant'])->group(function() {

            Route::prefix('restaurant')->group(function () {
                Route::get('/items', 'RestaurantController@items');
                Route::get('/categories', 'RestaurantController@categories');
                Route::get('/configurations', 'RestaurantConfigurationController@record');
            });

        });

    });
}
