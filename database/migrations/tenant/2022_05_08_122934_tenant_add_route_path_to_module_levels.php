<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddRoutePathToModuleLevels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_levels', function (Blueprint $table) {
            $table->string('route_path')->nullable()->after('route_name');
        });

        DB::table('module_levels')
            ->where('value', 'new_document')
            ->update([
                'route_path' => '/documents/create',
            ]);

        DB::table('module_levels')
            ->where('value', 'pos')
            ->update([
                'route_path' => '/pos',
            ]);

        DB::table('module_levels')
            ->where('value', 'configuration_company')
            ->update([
                'route_path' => '/companies/create',
            ]);

        DB::table('module_levels')
            ->where('value', 'users_establishments')
            ->update([
                'route_path' => '/establishments',
            ]);

        DB::table('module_levels')
            ->where('value', 'sale_notes')
            ->update([
                'route_path' => '/sale-notes',
            ]);

        DB::table('module_levels')
            ->where('value', 'order-note')
            ->update([
                'route_path' => '/order-notes',
            ]);

        DB::table('module_levels')
            ->where('value', 'items')
            ->update([
                'route_path' => '/items'
            ]);

        DB::table('module_levels')
            ->where('value', 'inventory')
            ->update([
                'route_path' => '/inventory',
            ]);


        DB::table('module_levels')
            ->where('value', 'users')
            ->update([
                'route_path' => '/users',
            ]);

        DB::table('module_levels')
            ->where('value', 'purchases_create')
            ->update([
                'route_path' => '/purchases/create',
                'description' => 'Nueva Compra'
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('module_levels', function (Blueprint $table) {
            $table->dropColumn('route_path');
        });
    }
}
