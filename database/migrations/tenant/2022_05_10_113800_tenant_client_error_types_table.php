<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantClientErrorTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::create('client_error_types', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
        });

        DB::table('client_error_types')->insert([
            ['id' => 'data_entry', 'name' => 'Errores de ingreso de datos'],
            ['id' => 'token_creation', 'name' => 'Errores en la creación del token de tarjeta'], 
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_error_types');
    }
}
