<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddZoneIdToPerson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        Schema::create('zones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

        });

        Schema::table('persons', function (Blueprint $table) {
            //
            $table->unsignedInteger('zone_id')->nullable();
            $table->unsignedInteger('seller_id')->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            //
            $table->unsignedInteger('zone_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->dropColumn('zone_id');
        });
        Schema::table('persons', function (Blueprint $table) {
            //
            $table->dropColumn('zone_id');
        });

        Schema::dropIfExists('zones');

    }
}
