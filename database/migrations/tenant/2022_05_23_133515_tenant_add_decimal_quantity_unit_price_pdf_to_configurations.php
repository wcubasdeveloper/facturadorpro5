<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDecimalQuantityUnitPricePdfToConfigurations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configurations', function (Blueprint $table) {
            $table->boolean('change_decimal_quantity_unit_price_pdf')->default(false);
            $table->integer('decimal_quantity_unit_price_pdf')->default(2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configurations', function (Blueprint $table) {
            $table->dropColumn('change_decimal_quantity_unit_price_pdf');
            $table->dropColumn('decimal_quantity_unit_price_pdf');
        });
    }
}
