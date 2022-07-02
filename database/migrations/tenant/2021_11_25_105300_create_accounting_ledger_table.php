<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;

    class CreateAccountingLedgerTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {

            Schema::create('cat_accounting_ledger_code_account', function (Blueprint $table) {
                $table->increments('id');
                $table->string('code_account')->comment('Codigo de plan de cuenta');
                $table->longText('name')->comment('Nombre de cuenta');
                $table->unsignedTinyInteger('disabled')->default(0)->nullable()->comment('Permite realizar modificaciones');
                $table->timestamps();

            });

            Schema::create('accounting_ledger_task', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedSmallInteger('month')->default(0)->nullable()->comment('Numero de mes');
                $table->unsignedMediumInteger('year')->default(0)->nullable()->comment('Numero de mes');
                $table->dateTime('last_rum')->nullable();

                $table->timestamps();


            });
            Schema::create('accounting_ledger', function (Blueprint $table) {
                //
                $table->increments('id');
                $table->unsignedSmallInteger('month')->default(0)->nullable()->comment('Numero de mes');
                $table->unsignedMediumInteger('year')->default(0)->nullable()->comment('Numero de mes');
                $table->date('date_of_report')->nullable();
                $table->text('code_account')->comment('Codigo de plan de cuenta');
                $table->longText('name')->comment('Nombre de cuenta');

                $table->float('last_month_total', 12, 2)->default(0)->comment('Debe ser el valor del total del mes pasado');
                $table->float('credits', 12, 2)->default(0)->comment('Créditos en el mes');
                $table->float('debs', 12, 2)->default(0)->comment('Debitos en el mes');
                $table->float('final_total', 12, 2)->default(0)->comment('Saldo Final de mes');
                $table->longText('serialize_data')->comment('datos serialziados en bruto.');

                $table->timestamps();

            });

            $data = [
                ['disabled' => 1, 'code_account' => '1', 'name' => 'Activos'],
                ['disabled' => 1, 'code_account' => '1.1', 'name' => 'Activos corrientes'],
                ['disabled' => 1, 'code_account' => '1.1.1', 'name' => 'Efectivo y equivalentes de efectivo'],
                ['disabled' => 1, 'code_account' => '1.1.1.1', 'name' => 'Caja'],
                ['disabled' => 1, 'code_account' => '1.1.1.2', 'name' => 'Bancos'],
                ['disabled' => 1, 'code_account' => '1.1.2', 'name' => 'Deudores comerciales y otras cuentas por cobrar'],
                ['disabled' => 1, 'code_account' => '1.1.2.0', 'name' => 'Activos por impuestos corrientes'],
                // ['disabled' => 1, 'code_account' => '1.1.2.0.1', 'name' => 'Impuestos a favor'],
                // ['disabled' => 1, 'code_account' => '1.1.2.0.2', 'name' => 'Retenciones a favor'],
                ['disabled' => 1, 'code_account' => '1.1.2.1', 'name' => 'Otras cuentas por cobrar'],
                ['disabled' => 1, 'code_account' => '1.1.3', 'name' => 'Inventarios'],
                ['disabled' => 1, 'code_account' => '1.1.4', 'name' => 'Inversiones a corto plazo'],
                ['disabled' => 1, 'code_account' => '1.1.5', 'name' => 'Otros activos corrientes'],
                ['disabled' => 1, 'code_account' => '1.2', 'name' => 'Activos no corrientes'],
                ['disabled' => 1, 'code_account' => '1.2.1', 'name' => 'Propiedad, planta y equipo (Activos fijos)'],
                ['disabled' => 1, 'code_account' => '1.2.2', 'name' => 'Otros Activos no corrientes'],
                ['disabled' => 1, 'code_account' => '2', 'name' => 'Pasivos'],
                ['disabled' => 1, 'code_account' => '2.1', 'name' => 'Pasivos corrientes'],
                ['disabled' => 1, 'code_account' => '2.1.1', 'name' => 'Cuentas por pagar'],
                ['disabled' => 1, 'code_account' => '2.1.1.1', 'name' => 'Otras cuentas por pagar'],
                ['disabled' => 1, 'code_account' => '2.1.2', 'name' => 'Provisiones'],
                ['disabled' => 1, 'code_account' => '2.1.3', 'name' => 'Obligaciones laborales y de seguridad social'],
                ['disabled' => 1, 'code_account' => '2.1.4', 'name' => 'Pasivos por impuestos corrientes'],
                ['disabled' => 1, 'code_account' => '2.1.4.1', 'name' => 'Impuestos por pagar'],
                ['disabled' => 1, 'code_account' => '2.1.4.2', 'name' => 'Retenciones por pagar'],
                ['disabled' => 1, 'code_account' => '2.1.5', 'name' => 'Cuentas por pagar con costo financiero'],
                ['disabled' => 1, 'code_account' => '2.1.6', 'name' => 'Obligaciones financieras'],
                ['disabled' => 1, 'code_account' => '2.1.6.1', 'name' => 'Tarjetas de crédito'],
                ['disabled' => 1, 'code_account' => '2.1.7', 'name' => 'Otros pasivos corrientes'],
                ['disabled' => 1, 'code_account' => '2.2', 'name' => 'Pasivos no corrientes'],
                ['disabled' => 1, 'code_account' => '2.2.1', 'name' => 'Préstamos a largo plazo'],
                ['disabled' => 1, 'code_account' => '2.2.2', 'name' => 'Otros pasivos no corrientes'],
                 ['disabled' => 1, 'code_account' => '3', 'name' => 'Patrimonio'],
                 ['disabled' => 1, 'code_account' => '3.1', 'name' => 'Capital social'],
                ['disabled' => 1, 'code_account' => '3.2', 'name' => 'Ganancias acumuladas'],
                ['disabled' => 1, 'code_account' => '3.3', 'name' => 'Ajustes por saldos iniciales'],
                ['disabled' => 1, 'code_account' => '3.3.1', 'name' => 'Ajustes iniciales en bancos'],
                ['disabled' => 1, 'code_account' => '3.3.2', 'name' => 'Ajustes iniciales en inventario'],
                ['disabled' => 1, 'code_account' => '4', 'name' => 'Ingresos'],
                ['disabled' => 1, 'code_account' => '4.1', 'name' => 'Ingresos de actividades ordinarias'],
                // ['disabled' => 1, 'code_account' => '4.1.1', 'name' => 'Ventas'],
                // ['disabled' => 1, 'code_account' => '4.1.2', 'name' => 'Devoluciones en ventas'],
                ['disabled' => 1, 'code_account' => '4.2', 'name' => 'Otros Ingresos'],
                ['disabled' => 1, 'code_account' => '4.2.1', 'name' => 'Otros ingresos diversos'],
            ];
            foreach($data as $toInsert){
                DB::table('cat_accounting_ledger_code_account')->insert($toInsert);
            }


        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('cat_accounting_ledger_code_account');
            Schema::dropIfExists('accounting_ledger_task');
            Schema::dropIfExists('accounting_ledger');

        }
    }
