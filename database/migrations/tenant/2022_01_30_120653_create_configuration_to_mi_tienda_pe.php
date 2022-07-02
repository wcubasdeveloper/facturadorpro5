<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class CreateConfigurationToMiTiendaPe extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {

            Schema::table('configurations', function (Blueprint $table) {
                $table->boolean('mi_tienda_pe')->default(false)->after('search_item_by_series');
            });
            Schema::create('configuration_mi_tienda_pe', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('establishment_id')->default(1)->nullable();
                $table->unsignedInteger('series_order_note_id')->default(0)->nullable();
                $table->unsignedInteger('series_document_ft_id')->default(0)->nullable();
                $table->unsignedInteger('series_document_bt_id')->default(0)->nullable();

                $table->unsignedInteger('user_id')->default(0)->nullable();
                $table->unsignedInteger('payment_destination_id')->default(0)->nullable();
                $table->string('currency_type_id')->nullable();
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
            Schema::dropIfExists('configuration_mi_tienda_pe');
            Schema::table('configurations', function (Blueprint $table) {
                $table->dropColumn('mi_tienda_pe');
            });
        }
    }
