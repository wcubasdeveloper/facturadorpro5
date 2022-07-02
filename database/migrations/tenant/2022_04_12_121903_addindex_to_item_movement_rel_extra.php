<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class AddindexToItemMovementRelExtra extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('item_movement_rel_extra', function (Blueprint $table) {
                //
                $table->index('item_status_id', 'index_item_status_id');
                $table->index('item_unit_business_id', 'index_item_unit_business_id');
                $table->index('item_mold_cavities_id', 'index_item_mold_cavities_id');
                $table->index('item_package_measurements_id', 'index_item_package_measurements_id');
                $table->index('item_units_per_package_id', 'index_item_units_per_package_id');
                $table->index('item_mold_properties_id', 'index_item_mold_properties_id');
                $table->index('item_product_family_id', 'index_item_product_family_id');
                $table->index('item_size_id', 'index_item_size_id');
            });

        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('item_movement_rel_extra', function (Blueprint $table) {
                //
                $table->dropIndex('index_item_status_id');
                $table->dropIndex('index_item_unit_business_id');
                $table->dropIndex('index_item_mold_cavities_id');
                $table->dropIndex('index_item_package_measurements_id');
                $table->dropIndex('index_item_units_per_package_id');
                $table->dropIndex('index_item_mold_properties_id');
                $table->dropIndex('index_item_product_family_id');
                $table->dropIndex('index_item_size_id');
            });
        }
    }
