<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOfficeToProcess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentary_processes', function (Blueprint $table) {
            //
            $table->longText('documentary_offices')->nullable()->comment('etapas que contiene');
            $table->longText('documentary_offices_order')->nullable('orden de las etapas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentary_processes', function (Blueprint $table) {
            //
            $table->dropColumn(['documentary_offices','documentary_offices_order']);
        });
    }
}
