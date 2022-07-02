<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurant_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code');
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });

        DB::table('restaurant_roles')->insert([
            [
                'name' => 'Mozo',
                'code' => 'MOZO',
                'description' => 'Usuario que genera pedidos en mesas',
            ],
            [
                'code' => 'CAJA',
                'name' => 'Caja',
                'description' => 'Usuario que genera pago de pedidos',
            ],
            [
                'code' => 'ADM',
                'name' => 'Administrador',
                'description' => 'Usuario con permisos totales',
            ],
            [
                'code' => 'KIT',
                'name' => 'Comanda/Cocina',
                'description' => 'Usuario con acceso a cocina',
            ],
            [
                'code' => 'BAR',
                'name' => 'Comanda/Bar',
                'description' => 'Usuario con acceso a bar',
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurant_roles');
    }
}
