<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class AddMorePropertysToItem extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            /*

                Peso(Gr)
                Ciclo
                ID Insumo
            */

            Schema::create('cat_item_status', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('name')->comment('Nombre del status');
                $table->timestamps();
            });
            Schema::create('cat_item_unit_business', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('name')->comment('Nombre de unidad de negocio');
                $table->timestamps();
            });
            Schema::create('cat_item_mold_cavities', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('name')->comment('Nombre de cavidades del molde');
                $table->timestamps();
            });
            Schema::create('cat_item_package_measurements', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('name')->comment('Nombre de medidas del paquete');
                $table->timestamps();
            });
            Schema::create('cat_item_units_per_package', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('name')->comment('Nombre de unidades por paquete');
                $table->timestamps();
            });
            Schema::create('cat_item_mold_properties', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('name')->comment('Nombre de propiedades por molde');
                $table->timestamps();
            });
            Schema::create('cat_item_product_family', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('name')->comment('Nombre de familia d eproductos');
                $table->timestamps();
            });


            Schema::create('item_status', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id');
                $table->unsignedInteger('cat_item_status_id');
                $table->tinyInteger('active')->default(1)->nullable()->comment('Define si se encuentra activo');
                $table->timestamps();
            });
            Schema::create('item_unit_business', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id');
                $table->unsignedInteger('cat_item_unit_business_id');
                $table->tinyInteger('active')->default(1)->nullable()->comment('Define si se encuentra activo');
                $table->timestamps();
            });
            Schema::create('item_mold_cavities', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id');
                $table->unsignedInteger('cat_item_mold_cavities_id');
                $table->tinyInteger('active')->default(1)->nullable()->comment('Define si se encuentra activo');
                $table->timestamps();
            });
            Schema::create('item_package_measurements', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id');
                $table->unsignedInteger('cat_item_package_measurements_id');
                $table->tinyInteger('active')->default(1)->nullable()->comment('Define si se encuentra activo');
                $table->timestamps();
            });
            Schema::create('item_units_per_package', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id');
                $table->unsignedInteger('cat_item_units_per_package_id');
                $table->tinyInteger('active')->default(1)->nullable()->comment('Define si se encuentra activo');
                $table->timestamps();
            });
            Schema::create('item_mold_properties', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id');
                $table->unsignedInteger('cat_item_mold_properties_id');
                $table->tinyInteger('active')->default(1)->nullable()->comment('Define si se encuentra activo');
                $table->timestamps();
            });
            Schema::create('item_product_family', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id');
                $table->unsignedInteger('cat_item_product_family_id');
                $table->tinyInteger('active')->default(1)->nullable()->comment('Define si se encuentra activo');
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
            // Estatus
            // Unidad Negocio
            // Cavidades Molde
            // Medida Pqt
            // Unids x Pqt
            // Propiedad Molde
            // Familia Prod
            Schema::dropIfExists('cat_item_status');
            Schema::dropIfExists('cat_item_unit_business');
            Schema::dropIfExists('cat_item_mold_cavities');
            Schema::dropIfExists('cat_item_package_measurements');
            Schema::dropIfExists('cat_item_units_per_package');
            Schema::dropIfExists('cat_item_mold_properties');
            Schema::dropIfExists('cat_item_product_family');
            Schema::dropIfExists('item_status');
            Schema::dropIfExists('item_unit_business');
            Schema::dropIfExists('item_mold_cavities');
            Schema::dropIfExists('item_package_measurements');
            Schema::dropIfExists('item_units_per_package');
            Schema::dropIfExists('item_mold_properties');
            Schema::dropIfExists('item_product_family');
        }
    }
