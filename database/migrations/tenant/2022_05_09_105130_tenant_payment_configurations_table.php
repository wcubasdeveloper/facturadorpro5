<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPaymentConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('payment_configurations', function (Blueprint $table) {
            $table->increments('id');
            
            $table->boolean('enabled_yape');
            $table->string('qrcode_yape')->nullable();
            $table->string('name_yape')->nullable();
            $table->string('telephone_yape')->nullable();

            $table->boolean('enabled_mp')->default(false);
            $table->string('access_token_mp')->nullable();
            $table->string('public_key_mp')->nullable();
            
            $table->timestamps();
        });


        DB::table('payment_configurations')->insert([
            'enabled_yape' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_configurations');
    }

}
