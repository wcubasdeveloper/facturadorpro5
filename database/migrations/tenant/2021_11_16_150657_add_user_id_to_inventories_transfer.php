<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserIdToInventoriesTransfer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventories_transfer', function (Blueprint $table) {
            //
            $table->unsignedInteger('user_id')->nullable()->default(0)->comment('usuario que crea el registro');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventories_transfer', function (Blueprint $table) {
            //
            $table->dropIfExists('user_id');
        });
    }
}
