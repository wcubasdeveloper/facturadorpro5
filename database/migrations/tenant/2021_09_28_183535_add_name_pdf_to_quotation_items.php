<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNamePdfToQuotationItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->longText('additional_information')->nullable()->comment('Informacion adicional');
            $table->unsignedInteger('warehouse_id')->default(0)->nullable()->comment('Id de warehouse');
            $table->longText('name_product_pdf')->nullable()->comment('Nombre de producto en el pdf');

            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quotation_items', function (Blueprint $table) {
            $table->dropColumn('additional_information');
            $table->dropColumn('warehouse_id');
            $table->dropColumn('name_product_pdf');
            //
        });
    }
}
