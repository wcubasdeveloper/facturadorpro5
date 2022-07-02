<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

    /**
     * Class CreateDigemidCatalog
     * @mixin Migration
     */
    class CreateDigemidCatalog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cat_digemid', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id')->comment('Id de la tabla de item');
            $table->longText('cod_digemid')->comment('Codigo digmid');
            $table->longText('nom_prod')->nullable()->comment('Nombre segun digemid');
            $table->longText('concent')->nullable()->comment('Dosificacion segun digemid');
            $table->longText('nom_form_farm')->nullable();
            $table->longText('nom_form_farm_simplif')->nullable();
            $table->longText('presentac')->nullable();
            $table->longText('fracciones')->nullable();
            $table->longText('fec_vcto_reg_sanitario')->nullable();
            $table->longText('num_reg_san')->nullable();
            $table->longText('nom_titular')->nullable();
            $table->longText('prices')->nullable();
            $table->unsignedInteger('max_prices')->nullable()->default(0);
            $table->unsignedTinyInteger('active')->default(0);
            $table->timestamp('last_update')->nullable();
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
        Schema::dropIfExists('cat_digemid');
    }
}
