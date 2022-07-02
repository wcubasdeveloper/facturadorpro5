<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddDiscountStockToCatTransferReasonTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('cat_transfer_reason_types', function (Blueprint $table) {
            $table->boolean('discount_stock')->default(false)->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('cat_transfer_reason_types', function (Blueprint $table) {
            $table->dropColumn('discount_stock');
        });

    }

}
