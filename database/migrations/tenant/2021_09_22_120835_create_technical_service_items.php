<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateTechnicalServiceItems extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('technical_service_items', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('technical_services_id')->default(0)->nullable()->comment('Id de technical_services');
                $table->unsignedInteger('item_id')->default(0)->nullable()->comment('Id de item');
                $table->json('item')->nullable()->comment('Json con el contenido de item');
                $table->float('quantity', 12, 4)->default(0)->nullable()->comment('Cantidad de item usado');
                $table->float('unit_value', 16, 6)->default(0)->nullable()->comment('unit_value');
                $table->longText('affectation_igv_type_id')->nullable()->comment('Tipo de afectacion dde igv. cat_affectation_igv_types');
                $table->float('total_base_igv', 12)->default(0)->nullable()->comment('Monto base del IGV');
                $table->float('percentage_igv', 12)->default(0)->nullable()->comment('');
                $table->float('total_igv', 12)->default(0)->nullable()->comment('');
                $table->longText('system_isc_type_id')->nullable()->comment('');
                $table->float('total_base_isc', 12)->default(0)->nullable()->comment('');
                $table->float('percentage_isc', 12)->default(0)->nullable()->comment('');
                $table->float('total_isc', 12)->default(0)->nullable()->comment('');
                $table->float('total_base_other_taxes', 12)->default(0)->nullable()->comment('');
                $table->float('percentage_other_taxes', 12)->default(0)->nullable()->comment('');
                $table->float('total_other_taxes', 12)->default(0)->nullable()->comment('');
                $table->float('total_plastic_bag_taxes', 6)->default(0)->nullable()->comment('');
                $table->float('total_taxes', 12)->default(0)->nullable()->comment('');
                $table->longText('price_type_id')->nullable()->comment('');
                $table->float('unit_price', 16, 6)->default(0)->nullable()->comment('');
                $table->float('total_value', 12)->default(0)->nullable()->comment('');
                $table->float('total_charge', 12)->default(0)->nullable()->comment('');
                $table->float('total_discount', 12)->default(0)->nullable()->comment('');
                $table->float('total', 12)->default(0)->nullable()->comment('');

                $table->json('attributes')->nullable()->comment('Atributos');
                $table->json('discounts')->nullable()->comment('Descuentos');
                $table->json('charges')->nullable()->comment('Cargos');

                $table->longText('additional_information')->nullable()->comment('Informacion adicional');
                $table->unsignedInteger('warehouse_id')->default(0)->nullable()->comment('Id de item');
                $table->longText('name_product_pdf')->nullable()->comment('Nombre de producto en el pdf');
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
            Schema::dropIfExists('technical_service_items');
        }
    }
