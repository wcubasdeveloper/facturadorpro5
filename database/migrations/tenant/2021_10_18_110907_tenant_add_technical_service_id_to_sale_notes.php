<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddTechnicalServiceIdToSaleNotes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_notes', function (Blueprint $table) {
            $table->unsignedInteger('technical_service_id')->nullable()->after('order_note_id');
            $table->foreign('technical_service_id')->references('id')->on('technical_services');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_notes', function (Blueprint $table) {
            $table->dropForeign(['technical_service_id']);
            $table->dropColumn('technical_service_id'); 
        });
    }
}
