<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddOldQuantityToItemLotGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_lots_group', function (Blueprint $table) {
            $table->decimal('old_quantity', 12, 4)->after("quantity")->default(0);
        });

        DB::statement("UPDATE item_lots_group  SET old_quantity = quantity where old_quantity = 0");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_lots_group', function (Blueprint $table) {
            $table->dropColumn('old_quantity');
        });
    }
}
