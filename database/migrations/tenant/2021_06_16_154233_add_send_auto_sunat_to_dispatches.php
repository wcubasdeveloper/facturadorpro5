<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSendAutoSunatToDispatches extends Migration
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
            $table->tinyInteger('auto_send_dispatchs_to_sunat')
                  ->default(1)
                  ->nullable()
                  ->comment('define si se mandan las guias automaticamente a sunat');
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
            $table->dropColumn('auto_send_dispatchs_to_sunat');
        });
    }
}
