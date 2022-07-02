<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddSubtotalToPurchaseSettlements extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('purchase_settlements', function (Blueprint $table) {
            $table->decimal('subtotal', 12, 2)->default(0)->after('total_value');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('purchase_settlements', function (Blueprint $table) {
            $table->dropColumn('subtotal');
        });
    }
}
