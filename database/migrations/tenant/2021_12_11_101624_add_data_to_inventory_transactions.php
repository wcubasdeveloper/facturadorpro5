<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddDataToInventoryTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('inventory_transactions')->insert([
            ['id' => '100', 'name' => 'Ingreso insumos por molino', 'type' => 'input'],
            ['id' => '101', 'name' => 'Salida por insumo', 'type' => 'output'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('inventory_transactions')->where('id', '100')->delete();
    }
}
