<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantChangeNullablePaymentToPaymentLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_links', function (Blueprint $table) {
            
            $table->integer('payment_id')->nullable()->change();
            $table->string('payment_type')->nullable()->change();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_links', function (Blueprint $table) {
            
            $table->integer('payment_id')->change();
            $table->string('payment_type')->change();

        });
    }
}
