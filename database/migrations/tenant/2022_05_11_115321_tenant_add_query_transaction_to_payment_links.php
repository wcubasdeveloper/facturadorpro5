<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddQueryTransactionToPaymentLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_links', function (Blueprint $table) {
            $table->boolean('query_transaction')->default(false)->after('uploaded_filename');
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
            $table->dropColumn('query_transaction');
        });
    }
}
