<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantTipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tips', function (Blueprint $table) {

            $table->increments('id');
            $table->char('soap_type_id', 2);
            $table->date('date')->index()->comment('Fecha de registro');
            
            $table->date('origin_date_of_issue')->index()->comment('Fecha del documento origen de la propina');
            $table->integer('origin_id');
            $table->string('origin_type');

            $table->string('worker_full_name')->index();
            $table->decimal('total', 12, 2);

            $table->index(['origin_id','origin_type'],'origin_index');
            $table->foreign('soap_type_id')->references('id')->on('soap_types');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tips');
    }

}
