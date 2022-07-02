<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCatAddressTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 

        Schema::create('cat_address_types', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('description');
        });

        DB::table('cat_address_types')->insert([
            ['id' => '01', 'description' => 'Punto de venta'],
            ['id' => '02', 'description' => 'Producción'],
            ['id' => '03', 'description' => 'Extracción'],
            ['id' => '04', 'description' => 'Explotación'],
            ['id' => '05', 'description' => 'Otros'],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cat_address_types');
    }
    
}
