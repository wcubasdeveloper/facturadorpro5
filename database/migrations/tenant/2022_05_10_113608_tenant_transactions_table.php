<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('payment_link_id');  
            $table->date('date')->index();  
            $table->time('time')->index();  
            $table->uuid('uuid')->unique();
            $table->string('description')->index();  
            $table->string('payment_id')->nullable()->index();  
            $table->decimal('amount', 16, 2)->index();  
            $table->string('transaction_state_id');  
            $table->foreign('payment_link_id')->references('id')->on('payment_links');
            $table->foreign('transaction_state_id')->references('id')->on('transaction_states');
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
        Schema::dropIfExists('transactions');
    }

}
