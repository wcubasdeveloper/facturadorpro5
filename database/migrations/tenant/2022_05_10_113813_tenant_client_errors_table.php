<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantClientErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('client_errors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->index();
            $table->string('client_error_type_id');
            $table->string('original_message');
            $table->string('user_message');
            $table->foreign('client_error_type_id')->references('id')->on('client_error_types');
        });
        

        DB::table('client_errors')->insert([
            ['code' => '205', 'client_error_type_id' => 'data_entry', 'original_message' => 'parameter cardNumber can not be null/empty', 'user_message' => 'Ingresa el número de tu tarjeta.'],
            ['code' => '208', 'client_error_type_id' => 'data_entry', 'original_message' => 'parameter cardExpirationMonth can not be null/empty', 'user_message' => 'Elige un mes.'],
            ['code' => '209', 'client_error_type_id' => 'data_entry', 'original_message' => 'parameter cardExpirationYear can not be null/empty', 'user_message' => 'Elige un año.'],
            ['code' => '212', 'client_error_type_id' => 'data_entry', 'original_message' => 'parameter docType can not be null/empty', 'user_message' => 'Ingresa tu tipo de documento.'],
            ['code' => '213', 'client_error_type_id' => 'data_entry', 'original_message' => 'The parameter cardholder.document.subtype can not be null or empty', 'user_message' => 'Ingresa tu documento.'],
            ['code' => '214', 'client_error_type_id' => 'data_entry', 'original_message' => 'parameter docNumber can not be null/empty', 'user_message' => 'Ingresa tu documento.'],
            ['code' => '220', 'client_error_type_id' => 'data_entry', 'original_message' => 'parameter cardIssuerId can not be null/empty', 'user_message' => 'Ingresa tu banco.'],
            ['code' => '221', 'client_error_type_id' => 'data_entry', 'original_message' => 'parameter cardholderName can not be null/empty', 'user_message' => 'Ingresa el nombre y apellido.'],
            ['code' => '224', 'client_error_type_id' => 'data_entry', 'original_message' => 'parameter securityCode can not be null/empty', 'user_message' => 'Ingresa el código de seguridad.'],
            ['code' => 'E301', 'client_error_type_id' => 'data_entry', 'original_message' => 'invalid parameter cardNumber', 'user_message' => 'Ingresa un número de tarjeta válido.'],
            ['code' => 'E302', 'client_error_type_id' => 'data_entry', 'original_message' => 'invalid parameter securityCode', 'user_message' => 'Revisa el código de seguridad.'],
            ['code' => '316', 'client_error_type_id' => 'data_entry', 'original_message' => 'invalid parameter cardholderName', 'user_message' => 'Ingresa un nombre válido.'],
            ['code' => '322', 'client_error_type_id' => 'data_entry', 'original_message' => '	invalid parameter docType', 'user_message' => 'El tipo de documento es inválido.'],
            ['code' => '323', 'client_error_type_id' => 'data_entry', 'original_message' => 'invalid parameter cardholder.document.subtype', 'user_message' => 'Revisa tu documento.'],
            ['code' => '324', 'client_error_type_id' => 'data_entry', 'original_message' => 'invalid parameter docNumber', 'user_message' => 'El documento es inválido.'],
            ['code' => '325', 'client_error_type_id' => 'data_entry', 'original_message' => 'invalid parameter cardExpirationMonth', 'user_message' => 'El mes es inválido'],
            ['code' => '326', 'client_error_type_id' => 'data_entry', 'original_message' => 'invalid parameter cardExpirationYear', 'user_message' => 'El año es inválido'],
            ['code' => 'default', 'client_error_type_id' => 'data_entry', 'original_message' => 'Otro código de error', 'user_message' => 'Revisa los datos.'],

            ['code' => '106', 'client_error_type_id' => 'token_creation', 'original_message' => 'Cannot operate between users from different countries', 'user_message' => 'No puedes realizar pagos a otros países.'],
            ['code' => '109', 'client_error_type_id' => 'token_creation', 'original_message' => 'Invalid number of shares for this payment_method_id', 'user_message' => 'El medio de pago no procesa pagos en installments cuotas. Elige otra tarjeta u otro medio de pago.'],
            ['code' => '126', 'client_error_type_id' => 'token_creation', 'original_message' => 'The action requested is not valid for the current payment state', 'user_message' => 'No pudimos procesar tu pago.'],
            ['code' => '129', 'client_error_type_id' => 'token_creation', 'original_message' => 'Cannot pay this amount with this paymentMethod', 'user_message' => 'El medio de pago no procesa pagos del monto seleccionado. Elige otra tarjeta u otro medio de pago.'],
            ['code' => '145', 'client_error_type_id' => 'token_creation', 'original_message' => 'Invalid users involved', 'user_message' => 'Una de las partes con la que intentas hacer el pago es de prueba y la otra es usuario real.'],
            ['code' => '150', 'client_error_type_id' => 'token_creation', 'original_message' => 'The payer_id cannot do payments currently', 'user_message' => 'No puedes realizar pagos.'],
            ['code' => '151', 'client_error_type_id' => 'token_creation', 'original_message' => 'The payer_id cannot do payments with this payment_method_id', 'user_message' => 'No puedes realizar pagos.'],
            ['code' => '160', 'client_error_type_id' => 'token_creation', 'original_message' => 'Collector not allowed to operate', 'user_message' => 'No pudimos procesar tu pago.'],
            ['code' => '204', 'client_error_type_id' => 'token_creation', 'original_message' => 'Unavailable payment_method', 'user_message' => 'El medio de pago no está disponible en este momento. Elige otra tarjeta u otro medio de pago.'],
            ['code' => '801', 'client_error_type_id' => 'token_creation', 'original_message' => 'Already posted the same request in the last minute', 'user_message' => 'Realizaste un pago similar hace instantes. Intenta de nuevo en unos minutos.'],
            ['code' => 'default', 'client_error_type_id' => 'token_creation', 'original_message' => 'Otro código de error', 'user_message' => 'No pudimos procesar tu pago.'],

        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_errors');
    }

}
