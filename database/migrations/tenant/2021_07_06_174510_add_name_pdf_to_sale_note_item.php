<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNamePdfToSaleNoteItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sale_note_items', function (Blueprint $table) {
            //
            $table->decimal('total_plastic_bag_taxes', 6, 2)
                  ->default(0)->after('total_other_taxes');
            $table->text('additional_information')->nullable()->after('charges');
            $table->longText('name_product_pdf')->nullable();

        });
        Schema::table('sale_notes', function (Blueprint $table) {
            $table->decimal('total_plastic_bag_taxes', 6, 2)
                  ->default(0)->after('total_other_taxes');
            $table->text('additional_information')->nullable()->after('legends');
            $table->unsignedInteger('seller_id')->default(0)->nullable();


        });
        }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sale_note_items', function (Blueprint $table) {
            //
            $table->dropColumn('total_plastic_bag_taxes');
            $table->dropColumn('additional_information');
            $table->dropColumn('name_product_pdf');
        });
        Schema::table('sale_note_items', function (Blueprint $table) {
            $table->dropColumn('total_plastic_bag_taxes');
            $table->dropColumn('additional_information');
            $table->dropColumn('seller_id');

        });
        }
}
