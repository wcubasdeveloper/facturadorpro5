<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsPurchaseIscToItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('items', function (Blueprint $table) {

            $table->decimal('purchase_percentage_isc', 12, 2)->default(0)->after('suggested_price');
            $table->string('purchase_system_isc_type_id')->nullable()->after('suggested_price');
            $table->boolean('purchase_has_isc')->default(false)->after('suggested_price');
            $table->foreign('purchase_system_isc_type_id')->references('id')->on('cat_system_isc_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {

            $table->dropForeign(['purchase_system_isc_type_id']);
            $table->dropColumn('purchase_system_isc_type_id');
            $table->dropColumn('purchase_percentage_isc');
            $table->dropColumn('purchase_has_isc');
            
        });
    }
}
