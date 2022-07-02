<?php

    use Illuminate\Support\Facades\Schema;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;

    class TransferAccountsPayment extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            //

            Schema::create('transfer_account_payments', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('origin_id')->default(0)->nullable()->comment('id Cuenta de origen');
                $table->text('origin_type')->nullable()->comment('Modelo de origen');
                $table->unsignedInteger('destiny_id')->default(0)->nullable()->comment('id Cuenta de destino');
                $table->text('destiny_type')->nullable()->comment('Modelo de destino');
                $table->decimal('amount', 9, 2)->default(0)->nullable()->comment('Monto a transferir');
                $table->dateTime('date_of_movement')->nullable()->comment('Fecha de movimiento');
                $table->unsignedInteger('user_id')->default(0)->nullable()->comment('Usuario que realiza el movimiento');
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
            //
            Schema::dropIfExists('transfer_account_payments');


        }
    }
