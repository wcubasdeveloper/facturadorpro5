<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateItemMovementRelExtraTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {

            Schema::create('cat_item_size', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('name')->comment('Nombre de unidad de medida');
                $table->timestamps();
            });


            Schema::create('item_size', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id');
                $table->unsignedInteger('cat_item_size_id');
                $table->tinyInteger('active')->default(1)->nullable()->comment('Define si se encuentra activo');
                $table->timestamps();
            });

            Schema::create('item_movement_rel_extra', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id')->default(0)->nullable();
                $table->unsignedInteger('item_movement_id')->default(0)->nullable();

                $table->unsignedInteger('item_color_id')->default(0)->nullable();
                $table->unsignedInteger('item_status_id')->default(0)->nullable();
                $table->unsignedInteger('item_unit_business_id')->default(0)->nullable();
                $table->unsignedInteger('item_mold_cavities_id')->default(0)->nullable();
                $table->unsignedInteger('item_package_measurements_id')->default(0)->nullable();
                $table->unsignedInteger('item_units_per_package_id')->default(0)->nullable();
                $table->unsignedInteger('item_mold_properties_id')->default(0)->nullable();
                $table->unsignedInteger('item_product_family_id')->default(0)->nullable();
                $table->unsignedInteger('item_size_id')->default(0)->nullable();
                $table->timestamps();
                $table->foreign('item_movement_id')->references('id')->on('item_movement');

            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('cat_item_size');
            Schema::dropIfExists('item_size');
            Schema::dropIfExists('item_movement_rel_extra');
        }
    }
