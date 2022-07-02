<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddFieldsToModuleLevels extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('module_levels', function (Blueprint $table) {
            $table->string('label_menu', 3)->nullable()->after('module_id');
            $table->string('route_name')->nullable()->after('module_id');
        });

        DB::table('module_levels')
            ->where('value', 'new_document')
            ->update([
                'label_menu' => 'NC',
                'route_name' => 'tenant.documents.create',
            ]);

        DB::table('module_levels')
            ->where('value', 'pos')
            ->update([
                'label_menu' => 'POS',
                'route_name' => 'tenant.pos.index',
            ]);

        DB::table('module_levels')
            ->where('value', 'configuration_company')
            ->update([
                'label_menu' => 'ME',
                'route_name' => 'tenant.companies.create',
            ]);

        DB::table('module_levels')
            ->where('value', 'users_establishments')
            ->update([
                'label_menu' => 'ES',
                'route_name' => 'tenant.establishments.index',
            ]);

        DB::table('module_levels')
            ->where('value', 'sale_notes')
            ->update([
                'label_menu' => 'NV',
                'route_name' => 'tenant.sale_notes.index',
            ]);

        DB::table('module_levels')
            ->where('value', 'order-note')
            ->update([
                'label_menu' => 'PED',
                'route_name' => 'tenant.order_notes.index',
            ]);

        DB::table('module_levels')
            ->where('value', 'items')
            ->update([
                'label_menu' => 'PRO',
                'route_name' => 'tenant.items.index'
            ]);

        DB::table('module_levels')
            ->where('value', 'inventory')
            ->update([
                'label_menu' => 'INV',
                'route_name' => 'inventory.index',
            ]);


        DB::table('module_levels')
            ->where('value', 'users')
            ->update([
                'label_menu' => 'USR',
                'route_name' => 'tenant.users.index',
            ]);

        DB::table('module_levels')
            ->where('value', 'purchases_create')
            ->update([
                'label_menu' => 'NC',
                'route_name' => 'tenant.purchases.create',
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
            $table->dropColumn('label_menu');
            $table->dropColumn('route_name');
        });
    }
}
