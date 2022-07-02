<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNameProductPdfToOrderNoteItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_note_items', function (Blueprint $table) {
            //
            $table
                ->decimal('total_plastic_bag_taxes',6,2)
                ->nullable()
                ->default(0)
                  ->comment('Impuesto bolsa plastica')
                  ->after('total_other_taxes');
            $table
                ->longText('additional_information')
                ->nullable()
                  ->comment('Informacion adcional')
                  ->after('charges');
            $table
                ->longText('name_product_pdf')
                ->nullable()
                  ->comment('Nombre del producto para el pdf')
                  ->after('warehouse_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_note_items', function (Blueprint $table) {
            //
            $table->dropColumn(['total_plastic_bag_taxes','additional_information','name_product_pdf']);
        });
    }
}
