<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsIntegratedQueryToCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('integrated_query_client_id')->nullable()->after('operation_amazonia');
            $table->string('integrated_query_client_secret')->nullable()->after('integrated_query_client_id');
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
            $table->dropColumn('integrated_query_client_id');
            $table->dropColumn('integrated_query_client_secret');
        });
    }
}
