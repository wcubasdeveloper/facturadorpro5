<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class AddGlobalIgvToPurchase extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('configurations', function (Blueprint $table) {
                //
                $table->unsignedTinyInteger('enabled_global_igv_to_purchase')->default(0)->nullable()->comment('Habilita el igv global en la compra. Sobreescribe has_igv del item');
        });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('configurations', function (Blueprint $table) {
                //
                $table->dropColumn('enabled_global_igv_to_purchase');
            });
        }
    }
