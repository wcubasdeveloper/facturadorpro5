<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantGeneralPaymentConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('general_payment_conditions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
        });

        DB::table('general_payment_conditions')->insert([
            ['id' => '01', 'name' => 'Contado'],
            ['id' => '02', 'name' => 'Crédito'],
            ['id' => '03', 'name' => 'Crédito con cuotas'],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_payment_conditions');
    }
}
