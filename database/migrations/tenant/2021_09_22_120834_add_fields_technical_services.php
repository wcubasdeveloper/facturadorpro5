<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class AddFieldsTechnicalServices extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::table('technical_services', function (Blueprint $table) {
                //


                $table->unsignedInteger('establishment_id')->default(0)->nullable()->after('soap_type_id');
                $table->json('establishment')->nullable()->after('establishment_id');
                $table->text('currency_type_id')->nullable()->after('customer');
                $table->text('payment_condition_id')->nullable()->after('currency_type_id');
                $table->text('payment_method_type_id')->nullable()->after('payment_condition_id');
                $table->unsignedInteger('seller_id')->default(0)->nullable()->after('payment_method_type_id');
                $table->decimal('exchange_rate_sale', 13, 3)->nullable()->default(0)->after('seller_id');

                $table->decimal('total_prepayment', 12)->nullable()->default(0)->after('exchange_rate_sale');
                $table->decimal('total_charge', 12)->nullable()->default(0)->after('total_prepayment');
                $table->decimal('total_discount', 12)->nullable()->default(0)->after('total_charge');
                $table->decimal('total_exportation', 12)->nullable()->default(0)->after('total_discount');
                $table->decimal('total_free', 12)->nullable()->default(0)->after('total_exportation');
                $table->decimal('total_taxed', 12)->nullable()->default(0)->after('total_free');
                $table->decimal('total_unaffected', 12)->nullable()->default(0)->after('total_taxed');
                $table->decimal('total_exonerated', 12)->nullable()->default(0)->after('total_unaffected');
                $table->decimal('total_igv', 12)->nullable()->default(0)->after('total_exonerated');
                $table->decimal('total_igv_free', 12)->nullable()->default(0)->after('total_igv');
                $table->decimal('total_base_isc', 12)->nullable()->default(0)->after('total_igv_free');
                $table->decimal('total_isc', 12)->nullable()->default(0)->after('total_base_isc');
                $table->decimal('total_base_other_taxes', 12)->nullable()->default(0)->after('total_isc');
                $table->decimal('total_other_taxes', 12)->nullable()->default(0)->after('total_base_other_taxes');
                $table->decimal('total_plastic_bag_taxes', 6)->nullable()->default(0)->after('total_other_taxes');
                $table->decimal('total_taxes', 12)->nullable()->default(0)->after('total_plastic_bag_taxes');
                $table->decimal('total_value', 12)->nullable()->default(0)->after('total_taxes');
                $table->decimal('subtotal', 12)->nullable()->default(0)->after('total_value');
                $table->decimal('total', 12)->nullable()->default(0)->after('subtotal');
                $table->unsignedTinyInteger('is_editable')->default(0)->nullable()->after('total');

            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('technical_services', function (Blueprint $table) {
                //

                $columns = [
                    'establishment_id',
                    'establishment',
                    'currency_type_id',
                    'payment_condition_id',
                    'payment_method_type_id',
                    'seller_id',
                    'exchange_rate_sale',
                    'total_prepayment',
                    'total_charge',
                    'total_discount',
                    'total_exportation',
                    'total_free',
                    'total_taxed',
                    'total_unaffected',
                    'total_exonerated',
                    'total_igv',
                    'total_igv_free',
                    'total_base_isc',
                    'total_isc',
                    'total_base_other_taxes',
                    'total_other_taxes',
                    'total_plastic_bag_taxes',
                    'total_taxes',
                    'total_value',
                    'subtotal',
                    'total',
                    'is_editable',
                ];
                $table->dropColumn($columns);


            });
        }
    }
