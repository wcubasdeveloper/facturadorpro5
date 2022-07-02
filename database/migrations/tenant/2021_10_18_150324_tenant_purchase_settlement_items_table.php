<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPurchaseSettlementItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_settlement_items', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('purchase_settlement_id');
            $table->unsignedInteger('item_id');
            $table->json('item');
            $table->decimal('quantity', 12, 2);
            $table->decimal('unit_value', 12, 2);

            $table->string('affectation_igv_type_id');
            $table->decimal('total_base_igv', 12, 2);
            $table->decimal('percentage_igv', 12, 2);
            $table->decimal('total_igv', 12, 2);

            $table->decimal('total_taxes', 12, 2);

            $table->string('price_type_id');
            $table->decimal('unit_price', 12, 2);

            $table->decimal('total_value', 12, 2);
            $table->decimal('total', 12, 2);

            $table->string('income_tax_affectation_igv_type_id')->nullable();
            $table->decimal('income_retention_percentage', 12, 2)->default(0);
            $table->decimal('income_retention_amount', 12, 2)->default(0);


            $table->foreign('purchase_settlement_id')->references('id')->on('purchase_settlements')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('income_tax_affectation_igv_type_id', 'p_s_i_income_tax_affectation_igv_type_id_fk')->references('id')->on('cat_affectation_igv_types');
            $table->foreign('affectation_igv_type_id')->references('id')->on('cat_affectation_igv_types');
            $table->foreign('price_type_id')->references('id')->on('cat_price_types');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_settlement_items');
    }
    
}
