<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemToMillItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mill_items', function (Blueprint $table) {
            //
            $table->json('item');
            $table->decimal('quantity', 12, 3)->default(0)->nullable()->comment('Peso dle insumo ');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mill_items', function (Blueprint $table) {
            //
            $table->dropColumn('item');
            $table->dropColumn('quantity');
        });
    }
}
