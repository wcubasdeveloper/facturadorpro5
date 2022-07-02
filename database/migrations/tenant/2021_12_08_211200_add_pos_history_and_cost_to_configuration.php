<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPosHistoryAndCostToConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configurations', function (Blueprint $table) {
            //
            $table->boolean('pos_history')->default(true)->after('show_service_on_pos');
            $table->boolean('pos_cost_price')->default(true)->after('show_service_on_pos');



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
            //
            $table->dropColumn('pos_history');
            $table->dropColumn('pos_cost_price');

        });
    }
}
