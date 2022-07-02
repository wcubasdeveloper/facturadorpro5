<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailSendLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_send_log', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('relation_id')->nullable()->default(0)->comment('Id de modelo');
            $table->unsignedInteger('type')->default(0)->nullable()->comment('Tipo de relacion');
            $table->longText('relation_model')->nullable()->comment('Modelo a relacion');
            $table->longText('file_line')->nullable()->comment('Archivo qu elo llama');
            $table->unsignedTinyInteger('sendit')->nullable()->default(1)->comment('Booleano para envio de correo');
            $table->text('email')->nullable()->comment('Correo de destino');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_send_log');
    }
}
