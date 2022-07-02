<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldToSendSaleNoteToOtherSite extends Migration
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
            $table
                ->boolean('send_data_to_other_server')
                ->default(0)
                ->comment('Habilita la posibilidad de enviar datos a otro servidor');
        });

        Schema::create('migration_configuration', function (Blueprint $table) {
            $table->increments('id');
            $table->string('url')->nullable();
            $table->string('api_key')->nullable();
            $table->timestamps();
        });

        Schema::create('sale_note_migration', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sale_notes_id');
            $table->unsignedInteger('user_id');
            $table->unsignedTinyInteger('success')->default(0);
            $table->string('url');
            $table->unsignedInteger('remote_id')->default(0);
            $table->string('number')->nullable();
            $table->longText('data')->nullable();
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
        Schema::table('configurations', function (Blueprint $table) {
            //
            $table->dropColumn('send_data_to_other_server');
        });

        Schema::dropIfExists('migration_configuration');
        Schema::dropIfExists('sale_note_migration');

    }
}
