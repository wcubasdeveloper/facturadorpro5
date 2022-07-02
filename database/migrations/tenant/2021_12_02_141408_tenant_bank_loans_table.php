<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    class TenantBankLoansTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('bank_loan_types', function (Blueprint $table) {
                $table->increments('id');
                $table->string('description');
                $table->timestamps();
            });

            DB::table('bank_loan_types')->insert([
                ['id' => '1', 'description' => 'Prestamo', 'created_at' => now(), 'updated_at' => now()],
            ]);

            Schema::create('bank_loan_reasons', function (Blueprint $table) {
                $table->increments('id');
                $table->string('description');
            });


            DB::table('bank_loan_reasons')->insert([
                ['id' => '1', 'description' => 'Varios'],
            ]);


            Schema::create('bank_loans', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('bank_loan_type_id');
                $table->unsignedInteger('establishment_id');
                $table->unsignedInteger('bank_id');
                $table->unsignedInteger('bank_account_id');
                $table->string('currency_type_id');
                $table->uuid('external_id');
                $table->integer('number');
                $table->date('date_of_issue');
                $table->time('time_of_issue');
                $table->json('bank');
                $table->decimal('exchange_rate_sale', 12, 2)->default(0)->nullable();
                $table->decimal('total', 12, 2)->default(0)->nullable();
                $table->decimal('total_interest', 12, 2)->default(0)->nullable();
                $table->decimal('total_ingress', 12, 2)->default(0)->nullable();
                $table->char('soap_type_id', 2)->nullable();
                $table->char('state_type_id')->nullable();
                $table->timestamps();
            });

            Schema::create('bank_loan_items', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('bank_loan_id');
                $table->text('description');
                $table->decimal('total', 12, 2)->default(0)->nullable();
                $table->decimal('total_interest', 12, 2)->default(0)->nullable();
                $table->decimal('total_ingress', 12, 2)->default(0)->nullable();
            });

            Schema::create('bank_loan_payments', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('bank_loan_id');
                $table->date('date_of_payment');
                // $table->unsignedInteger('bank_loan_method_type_id');
                $table->char('payment_method_type_id', 2);

                $table->boolean('has_card')->default(false);
                $table->char('card_brand_id', 2)->nullable();
                $table->string('reference')->nullable();
                $table->decimal('payment', 12, 2);


            });

            Schema::create('bank_loan_fee', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('bank_loan_id');
                $table->date('date');
                $table->string('currency_type_id');
                $table->decimal('amount', 12, 2);
                $table->char('payment_method_type_id',2)->nullable()->comment('Relacion con el metodo de pago, Nulo es pago a cuotas');


            });
            Schema::table('cash_documents', function (Blueprint $table) {
                $table->unsignedInteger('bank_loan_payment_id')->nullable();
            });

        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::table('cash_documents', function (Blueprint $table) {
                $table->dropColumn('bank_loan_payment_id');
            });

            Schema::dropIfExists('bank_loan_types');

            Schema::dropIfExists('bank_loans');
            Schema::dropIfExists('bank_loan_items');
            Schema::dropIfExists('bank_loan_payments');
            Schema::dropIfExists('bank_loan_reasons');
            Schema::dropIfExists('bank_loan_fee');

        }
    }
