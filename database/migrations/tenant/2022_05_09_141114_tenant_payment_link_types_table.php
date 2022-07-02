<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPaymentLinkTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('payment_link_types', function (Blueprint $table) {

            $table->char('id', 2)->primary();
            $table->string('description');
            
        });


        DB::table('payment_link_types')->insert([
            [
                'id' => '01',
                'description' => 'Yape',
            ],
            [
                'id' => '02',
                'description' => 'Mercado Pago',
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
        Schema::dropIfExists('payment_link_types');
    }

}
