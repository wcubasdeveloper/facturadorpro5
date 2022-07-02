<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeTypeColumnQuantityToItemSupplies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_supplies', function (Blueprint $table) {
            $table->decimal('quantity', 13, 3)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_supplies', function (Blueprint $table) {
            $table->integer('quantity')->change();
        });
    }
}
