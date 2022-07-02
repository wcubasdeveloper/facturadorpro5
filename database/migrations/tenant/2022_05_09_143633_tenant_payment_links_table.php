<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPaymentLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_links', function (Blueprint $table) {

            $table->increments('id');
            $table->char('soap_type_id', 2);
            $table->uuid('uuid')->unique();
            $table->unsignedInteger('user_id');
            $table->char('payment_link_type_id', 2);
            $table->integer('payment_id');
            $table->string('payment_type');
            $table->index(['payment_id','payment_type'],'payment_index');
            
            $table->decimal('total', 12, 2);
            $table->string('uploaded_filename')->nullable();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('soap_type_id')->references('id')->on('soap_types');
            $table->foreign('payment_link_type_id')->references('id')->on('payment_link_types');

            $table->timestamps();
            
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_links');
    }

}
