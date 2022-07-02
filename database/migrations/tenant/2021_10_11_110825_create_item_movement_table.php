<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateItemMovementTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('item_movement', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id')->default(0)->nullable()->comment('Relacion con item');
                $table->decimal('quantity', 12, 4)->default(0)->nullable()->comment('Cantidad de venta del item');
                $table->timestamp('date_of_movement', 0)->nullable();
                $table->unsignedTinyInteger('countable')->default(0)->nullable()->comment('Define si se toma en cuenta para el conteo de inventario');
                $table->unsignedInteger('establishment_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('contract_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('devolution_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('dispatch_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('document_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('expense_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('fixed_asset_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('fixed_asset_purchase_item_id')->default(0)->nullable()->comment('Relacion con la tabla');

                $table->unsignedInteger('order_form_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('order_note_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('purchase_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('purchase_order_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('purchase_quotation_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('quotation_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('sale_note_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('sale_opportunity_item_id')->default(0)->nullable()->comment('Relacion con la tabla');
                $table->unsignedInteger('technical_service_item_id')->default(0)->nullable()->comment('Relacion con la tabla');


                $table->timestamps();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('item_movement');
        }
    }
