<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToClient extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function (Blueprint $table) {
            //
            $table
                ->longText('observation')
                ->nullable()
                ->comment('Observaciones')
                ->after('enabled');
            $table
                ->longText('zone')
                ->nullable()
                ->comment('Zona')
                ->after('enabled');
            $table
                ->text('website')
                ->nullable()
                ->comment('Sitio Web')
                ->after('enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('persons', function (Blueprint $table) {
            //
            $table->dropColumn(['website','zone','observation']);

        });
    }
}
