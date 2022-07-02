<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsSendPseToDispatches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('dispatches', function (Blueprint $table) {

            $table->json('response_send_cdr_pse')->nullable()->after('soap_shipping_response');
            $table->json('response_signature_pse')->nullable()->after('soap_shipping_response');
            $table->boolean('send_to_pse')->default(false)->after('soap_shipping_response');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dispatches', function (Blueprint $table) {

            $table->dropColumn('send_to_pse');
            $table->dropColumn('response_signature_pse');
            $table->dropColumn('response_send_cdr_pse');

        });
    }
}
