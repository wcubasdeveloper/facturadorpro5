<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddSoapTypeIdToTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->char('soap_type_id', 2)->nullable()->after('payment_link_id');
            $table->foreign('soap_type_id')->references('id')->on('soap_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {

            $table->dropForeign(['soap_type_id']);
            $table->dropColumn('soap_type_id');

        });
    }
}
