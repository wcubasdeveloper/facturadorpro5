<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsSendPseToDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->json('response_send_cdr_pse')->nullable()->after('response_regularize_shipping');
            $table->json('response_signature_pse')->nullable()->after('response_regularize_shipping');
            $table->boolean('send_to_pse')->default(false)->after('response_regularize_shipping');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {

            $table->dropColumn('send_to_pse');
            $table->dropColumn('response_signature_pse');
            $table->dropColumn('response_send_cdr_pse');
            
        });
    }
}
