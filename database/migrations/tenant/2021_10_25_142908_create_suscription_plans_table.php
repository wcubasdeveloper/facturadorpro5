<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateSuscriptionPlansTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('cat_periods', function (Blueprint $table) {
                $table->increments('id');
                $table->char('period', 1)->comment('Define si es dia, mes o año - D/M/Y');
                $table->text('name')->comment('Nombre del periodo');
                $table->tinyInteger('active')->default(0)->comment('Si esta activo');
                $table->timestamps();

            });

            Schema::create('suscription_plans', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('cat_period_id')->default(0)->nullable()->comment('Relacion con el periodo de tiempo');
                $table->text('name')->comment('Nombre del plan');
                $table->longText('description')->comment('Descripcion del plan');
                $table->float('total', 12, 2)->default(0)->nullable()->comment('El total del costo del plan');

                $table->string('currency_type_id')->nullable();
                $table->string('payment_method_type_id')->nullable();
                $table->unsignedInteger('quantity_period')->nullable();
                $table->float('exchange_rate_sale', 13, 3)->default(0)->nullable();
                $table->float('total_prepayment', 12, 2)->default(0)->nullable();
                $table->float('total_charge', 12, 2)->default(0)->nullable();
                $table->float('total_discount', 12, 2)->default(0)->nullable();
                $table->float('total_exportation', 12, 2)->default(0)->nullable();
                $table->float('total_free', 12, 2)->default(0)->nullable();
                $table->float('total_taxed', 12, 2)->default(0)->nullable();
                $table->float('total_unaffected', 12, 2)->default(0)->nullable();
                $table->float('total_exonerated', 12, 2)->default(0)->nullable();
                $table->float('total_igv', 12, 2)->default(0)->nullable();
                $table->float('total_igv_free', 12, 2)->default(0)->nullable();
                $table->float('total_base_isc', 12, 2)->default(0)->nullable();
                $table->float('total_isc', 12, 2)->default(0)->nullable();
                $table->float('total_base_other_taxes', 12, 2)->default(0)->nullable();
                $table->float('total_other_taxes', 12, 2)->default(0)->nullable();
                $table->float('total_taxes', 12, 2)->default(0)->nullable();
                $table->float('total_value', 12, 2)->default(0)->nullable();
                $table->json('charges')->nullable();
                $table->json('attributes')->nullable();
                $table->json('discounts')->nullable();
                $table->json('prepayments')->nullable();
                $table->json('related')->nullable();
                $table->json('perception')->nullable();
                $table->json('detraction')->nullable();
                $table->json('legends')->nullable();
                $table->longText('terms_condition')->nullable();
                $table->timestamps();
            });
            Schema::create('item_rel_suscription_plans', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('item_id')->default(0)->nullable()->comment('Relacion con items');
                $table->json('item')->nullable();

                $table->unsignedInteger('suscription_plan_id')->default(0)->nullable()->comment('Relacion con planes de suscripcion');

                $table->decimal('quantity', 12)->default(0)->nullable();
                $table->decimal('unit_value', 16, 2)->default(0)->nullable();
                $table->char('affectation_igv_type_id', 255)->nullable();
                $table->decimal('total_base_igv', 12)->default(0)->nullable();
                $table->decimal('percentage_igv', 12)->default(0)->nullable();
                $table->decimal('total_igv', 12)->default(0)->nullable();
                $table->char('system_isc_type_id', 255)->nullable();
                $table->decimal('total_base_isc', 12, 2)->default(0)->nullable();
                $table->decimal('percentage_isc', 12, 2)->default(0)->nullable();
                $table->decimal('total_isc', 12, 2)->default(0)->nullable();
                $table->decimal('total_base_other_taxes', 12, 2)->default(0)->nullable();
                $table->decimal('percentage_other_taxes', 12, 2)->default(0)->nullable();
                $table->decimal('total_other_taxes', 12, 2)->default(0)->nullable();
                $table->decimal('total_taxes', 12, 2)->default(0)->nullable();
                $table->char('price_type_id', 255)->nullable();
                $table->decimal('unit_price', 16, 6)->default(0)->nullable();
                $table->decimal('total_value', 12, 2)->default(0)->nullable();
                $table->decimal('total_charge', 12, 2)->default(0)->nullable();
                $table->decimal('total_discount', 12, 2)->default(0)->nullable();
                $table->decimal('total', 12, 2)->default(0)->nullable();
                $table->json('attributes')->nullable();
                $table->json('discounts')->nullable();
                $table->json('charges')->nullable();
                $table->text('additional_information')->nullable();
                $table->unsignedInteger('warehouse_id')->default(0)->nullable();
                $table->longText('name_product_pdf')->nullable();

                $table->timestamps();
            });
            Schema::create('user_rel_suscription_plans', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id')->default(0)->nullable()->comment('Relacion con usuario');
                $table->unsignedInteger('suscription_plan_id')->default(0)->nullable()->comment('Relacion con planes de suscripcion');
                $table->unsignedInteger('cat_period_id')->default(0)->nullable()->comment('Relacion con el periodo de tiempo');
                $table->json('items')->comment('Pega los items relacionados a modo de standar');
                $table->tinyInteger('editable')->default(0)->comment('Si ya ha sido adquirido, no puede ser modificado');
                $table->tinyInteger('deletable')->default(0)->comment('Si ya ha sido adquirido, no puede ser borrado');
                $table->date('start_date')->nullable()->comment('Fecha de inicio');

                $table->text('sale_notes')->nullable();
                $table->text('documents')->nullable();
                $table->text('dates_of_documents')->nullable();
                $table->date('automatic_date_of_issue')->nullable();
                $table->unsignedInteger('customer_id')->default(0)->nullable();
                $table->json('customer');
                $table->unsignedInteger('parent_customer_id')->default(0)->nullable();
                $table->json('parent_customer');
                $table->unsignedInteger('children_customer_id')->default(0)->nullable();
                $table->json('children_customer');
                $table->unsignedInteger('quantity_period')->default(0)->nullable();
                $table->unsignedInteger('apply_concurrency')->default(0);
                $table->unsignedInteger('enabled_concurrency')->default(1)->comment('Se pasará a nota de ventas como activo');


                $table->string('currency_type_id')->nullable();
                $table->string('payment_method_type_id')->nullable();

                $table->float('exchange_rate_sale', 13, 3)->default(0)->nullable();
                $table->float('total_prepayment', 12, 2)->default(0)->nullable();
                $table->float('total_charge', 12, 2)->default(0)->nullable();
                $table->float('total_discount', 12, 2)->default(0)->nullable();
                $table->float('total_exportation', 12, 2)->default(0)->nullable();
                $table->float('total_free', 12, 2)->default(0)->nullable();
                $table->float('total_taxed', 12, 2)->default(0)->nullable();
                $table->float('total_unaffected', 12, 2)->default(0)->nullable();
                $table->float('total_exonerated', 12, 2)->default(0)->nullable();
                $table->float('total_igv', 12, 2)->default(0)->nullable();
                $table->float('total_igv_free', 12, 2)->default(0)->nullable();
                $table->float('total_base_isc', 12, 2)->default(0)->nullable();
                $table->float('total_isc', 12, 2)->default(0)->nullable();
                $table->float('total_base_other_taxes', 12, 2)->default(0)->nullable();
                $table->float('total_other_taxes', 12, 2)->default(0)->nullable();
                $table->float('total_taxes', 12, 2)->default(0)->nullable();
                $table->float('total_value', 12, 2)->default(0)->nullable();
                $table->decimal('total', 12, 2)->default(0)->nullable();
                $table->json('charges')->nullable();
                $table->json('attributes')->nullable();
                $table->json('discounts')->nullable();
                $table->json('prepayments')->nullable();
                $table->json('related')->nullable();
                $table->json('perception')->nullable();
                $table->json('detraction')->nullable();
                $table->json('legends')->nullable();
                $table->longText('terms_condition')->nullable();
                $table->timestamps();
            });
            $catPeriods = [];
            /*
            $catPeriods[] = [
                'Id' => count($catPeriods) + 1,
                'period' => 'D',
                'name' => 'Diario',
                'active' => 1,
            ];*/


            $catPeriods[] = [
                'Id' => count($catPeriods) + 1,
                'period' => 'M',
                'name' => 'Mensual',
                'active' => 1,
            ];
            $idyear = count($catPeriods) + 1;

            $catPeriods[] = [
                'Id' => $idyear,
                'period' => 'Y',
                'name' => 'Anual',
                'active' => 1,
            ];

            DB::table('cat_periods')->insert($catPeriods);
            $suscriptionPlans = [];

            $suscriptionPlans[] = [
                'Id' => count($suscriptionPlans) + 1,
                'cat_period_id' => 1,
                'name' => 'Matricula Escolar',
                'description' => 'Demostración de matricula escolar',
                'total' => 1,
                'payment_method_type_id' => '01',
                'currency_type_id' => 'PEN',
                'quantity_period' => 12,

            ];
            DB::table('suscription_plans')->insert($suscriptionPlans);


        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('cat_periods');
            Schema::dropIfExists('suscription_plans');
            Schema::dropIfExists('item_rel_suscription_plans');
            Schema::dropIfExists('user_rel_suscription_plans');
        }
    }
