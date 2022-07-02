<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddColumnsUnknownErrorToSummaries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->boolean('unknown_error_status_response')->default(false)->after('soap_shipping_response');
            $table->boolean('manually_regularized')->default(false)->after('unknown_error_status_response');
            $table->json('error_manually_regularized')->nullable()->after('manually_regularized');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('summaries', function (Blueprint $table) {
            $table->dropColumn('unknown_error_status_response');
            $table->dropColumn('manually_regularized');
            $table->dropColumn('error_manually_regularized');
        });
    }
}
