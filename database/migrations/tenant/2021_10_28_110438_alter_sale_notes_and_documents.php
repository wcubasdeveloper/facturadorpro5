<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    /**
     *
     */
    class AlterSaleNotesAndDocuments extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            // Se usa como base, Nota de venta - sale_notes

            Schema::table('sale_notes', function (Blueprint $table) {
                $table->unsignedInteger('user_rel_suscription_plan_id')->default(0)->nullable()->comment('Relacion con suscripciones')->after('document_id');
            });
            Schema::table('documents', function (Blueprint $table) {
                $table->unsignedInteger('user_rel_suscription_plan_id')->default(0)->nullable()->comment('Relacion con suscripciones')->after('sale_note_id');
                $table->unsignedTinyInteger('apply_concurrency')->default(0)->nullable()->after('exchange_rate_sale');
                $table->unsignedTinyInteger('enabled_concurrency')->default(0)->nullable()->after('exchange_rate_sale');
                $table->integer('quantity_period')->nullable()->after('exchange_rate_sale');
                $table->string('type_period')->index()->nullable()->after('exchange_rate_sale');
                $table->date('automatic_date_of_issue')->nullable()->after('exchange_rate_sale');
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('sale_notes', function (Blueprint $table) {
                 $table->dropColumn('user_rel_suscription_plan_id');

            });
            Schema::table('documents', function (Blueprint $table) {
               $table->dropColumn('user_rel_suscription_plan_id');
               $table->dropColumn('apply_concurrency');
               $table->dropColumn('enabled_concurrency');
               $table->dropColumn('quantity_period');
               $table->dropColumn('type_period');
               $table->dropColumn('automatic_date_of_issue');


            });
        }


    }
