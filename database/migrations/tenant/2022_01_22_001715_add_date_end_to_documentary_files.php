<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateEndToDocumentaryFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentary_files', function (Blueprint $table) {
            //
            $table->dateTime('date_end')->nullable()->comment('Fecha de finalización');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentary_files', function (Blueprint $table) {
            //
            $table->dropIfExists('date_end');
        });
    }
}
