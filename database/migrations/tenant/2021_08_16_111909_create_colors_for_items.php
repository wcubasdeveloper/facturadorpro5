<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateColorsForItems extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('cat_colors_items', function (Blueprint $table) {
                $table->increments('id');
                $table->longText('name')->comment('Nombre del color');
                $table->timestamps();
            });
            Schema::create('item_color', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id');
                $table->unsignedInteger('cat_colors_item_id');
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
            Schema::dropIfExists('cat_colors_items');
            Schema::dropIfExists('item_color');
        }
    }
