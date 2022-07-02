<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_configurations', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('menu_pos');
            $table->boolean('menu_order');
            $table->boolean('menu_tables');
            $table->string('first_menu');
        });
        DB::table('restaurant_configurations')->insert([
            [
                'menu_pos' => true,
                'menu_order' => true,
                'menu_tables' => true,
                'first_menu' => 'POS',
            ],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_configurations');
    }
}
