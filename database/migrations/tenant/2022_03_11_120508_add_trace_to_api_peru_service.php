<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTraceToApiPeruService extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('track_api_peru_services', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->unsignedInteger('service')->nullable()->default(0)->comment('Tipo de servicio  1 => sunat/dni, 2 => validacion_multiple_cpe, 3 => CPE, 4 => tipo_de_cambio, 5 => printer_ticket');
            $table->timestamp('date_of_issue')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('track_api_peru_services');

    }
}
