<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;


    /**
     * Class AddPharmacyToItems
     *
     * @mixin Migration
     */
    class AddPharmacyToItems extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up() {
            Schema::table('items', function (Blueprint $table) {
                //
                $table->text('cod_digemid')->nullable()->comment('Codigo de producto DIGEMID');
                $table->text('sanitary')->nullable()->comment('Registro sanitario');
            });
            Schema::table('companies', function (Blueprint $table) {
                //
                $table->text('cod_digemid')->nullable()->comment('Codigo de establecimiento DIGEMID');
            });
            Schema::table('configurations', function (Blueprint $table) {
                //
                $table
                    ->boolean('is_pharmacy')
                    ->default(0)
                    ->comment('Establece si se activa el modulo de farmacia');

            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down() {
            Schema::table('items', function (Blueprint $table) {
                //
                $table->dropColumn(['cod_digemid', 'sanitary']);
            });
            Schema::table('companies', function (Blueprint $table) {
                //
                $table->dropColumn(['cod_digemid']);
            });
            Schema::table('configurations', function (Blueprint $table) {
                //
                $table->dropColumn(['is_pharmacy']);
            });
        }
    }
