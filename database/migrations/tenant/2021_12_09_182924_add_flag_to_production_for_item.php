<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class AddFlagToProductionForItem extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('items', function (Blueprint $table) {
                //
                $table->boolean('is_for_production')->default(false)->comment('Define si es compuesto para produccion');
            });
            Schema::create('mill', function (Blueprint $table) {
                $table->increments('id');
                $table->date('date_start')->nullable();
                $table->time('time_start')->nullable();
                $table->date('date_end')->nullable();
                $table->time('time_end')->nullable();
                $table->unsignedInteger('user_id')->default(0)->nullable();
                $table->timestamps();
            });
            Schema::create('mill_items', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id')->default(0)->nullable();
                $table->unsignedInteger('mill_id')->default(0)->nullable();
                $table->decimal('height_to_mill', 12, 3)->default(0)->nullable()->comment('Peso de entrada');
                $table->decimal('total_height', 12, 3)->default(0)->nullable()->comment('Peso dle insumo ');
                $table->timestamps();
            });
            Schema::create('production', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->default(0)->nullable();
                $table->unsignedInteger('item_id')->default(0)->nullable();
                $table->unsignedInteger('inventory_id_reference')->nullable();
                $table->decimal('quantity', 12, 4)->default(0)->nullable()->comment('Peso dle insumo ');
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
            Schema::table('items', function (Blueprint $table) {
                //
                $table->dropColumn('is_for_production');
            });
            Schema::dropIfExists('mill');
            Schema::dropIfExists('mill_items');
            Schema::dropIfExists('production');

        }
    }
