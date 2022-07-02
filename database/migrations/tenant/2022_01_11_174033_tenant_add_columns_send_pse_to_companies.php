<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsSendPseToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('url_signature_pse')->nullable()->after('integrated_query_client_secret');
            $table->string('url_send_cdr_pse')->nullable()->after('integrated_query_client_secret');
            $table->boolean('send_document_to_pse')->default(false)->after('integrated_query_client_secret');
            $table->string('client_id_pse')->default('8')->after('integrated_query_client_secret');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('send_document_to_pse');
            $table->dropColumn('url_send_cdr_pse');
            $table->dropColumn('url_signature_pse');
            $table->dropColumn('client_id_pse');
        });
    }
}
