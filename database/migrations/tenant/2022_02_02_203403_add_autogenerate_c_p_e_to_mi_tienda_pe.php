<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutogenerateCPEToMiTiendaPe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('configuration_mi_tienda_pe', function (Blueprint $table) {
            //
            $table->unsignedTinyInteger('autogenerate')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuration_mi_tienda_pe', function (Blueprint $table) {
            //
            $table->dropColumn('autogenerate');

        });
    }
}
