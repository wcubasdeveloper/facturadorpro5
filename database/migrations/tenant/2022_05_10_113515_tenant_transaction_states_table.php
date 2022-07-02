<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantTransactionStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_states', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name')->index();
            $table->boolean('success')->index();
            $table->string('status')->index();
            $table->string('status_detail')->index();
            $table->string('original_message');
            $table->string('user_message');
        });

        
        DB::table('transaction_states')->insert([
            ['id' => '00', 'name' => 'Rechazado (Error desconocido)', 'success' => false, 'status' => 'other', 'status_detail' => 'other', 'original_message' => 'Error desconocido', 'user_message' => 'Lo sentimos, ocurrió un error inesperado.'],
            ['id' => '01', 'name' => 'Aceptado', 'success' => true, 'status' => 'approved', 'status_detail' => 'accredited', 'original_message' => '¡Listo! Se acreditó tu pago. En tu resumen verás el cargo de amount como statement_descriptor.', 'user_message' => '¡Listo! Se acreditó tu pago. En tu resumen verás el cargo del pago.'],
            ['id' => '02', 'name' => 'En proceso', 'success' => true, 'status' => 'in_process', 'status_detail' => 'pending_contingency', 'original_message' => 'Estamos procesando tu pago. No te preocupes, menos de 2 días hábiles te avisaremos por e-mail si se acreditó.', 'user_message' => 'Estamos procesando tu pago. No te preocupes, en menos de 2 días hábiles te avisaremos por e-mail si se acreditó.'],
            ['id' => '03', 'name' => 'En proceso', 'success' => true, 'status' => 'in_process', 'status_detail' => 'pending_review_manual', 'original_message' => 'Estamos procesando tu pago. No te preocupes, menos de 2 días hábiles te avisaremos por e-mail si se acreditó o si necesitamos más información.', 'user_message' => 'Estamos procesando tu pago. No te preocupes, en menos de 2 días hábiles te avisaremos por e-mail si se acreditó o si necesitamos más información.'],
            ['id' => '04', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_bad_filled_card_number', 'original_message' => 'Revisa el número de tarjeta.', 'user_message' => 'Lo sentimos, ocurrió un inconveniente. Revisa el número de tarjeta.'],
            ['id' => '05', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_bad_filled_date', 'original_message' => 'Revisa la fecha de vencimiento.', 'user_message' => 'Lo sentimos, ocurrió un inconveniente. Revisa la fecha de vencimiento.'],
            ['id' => '06', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_bad_filled_other', 'original_message' => 'Revisa los datos.', 'user_message' => 'Lo sentimos, ocurrió un inconveniente. Revisa los datos.'],
            ['id' => '07', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_bad_filled_security_code', 'original_message' => 'Revisa el código de seguridad de la tarjeta.', 'user_message' => 'Lo sentimos, ocurrió un inconveniente. Revisa el código de seguridad de la tarjeta.'],
            ['id' => '08', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_blacklist', 'original_message' => 'No pudimos procesar tu pago.', 'user_message' => 'Lo sentimos, ocurrió un inconveniente. No pudimos procesar tu pago.'],
            ['id' => '09', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_call_for_authorize', 'original_message' => 'Debes autorizar ante payment_method_id el pago de amount.', 'user_message' => 'Debes autorizar ante el medio de pago, el pago a realizar.'],
            ['id' => '10', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_card_disabled', 'original_message' => 'Llama a payment_method_id para activar tu tarjeta o usa otro medio de pago. El teléfono está al dorso de tu tarjeta.', 'user_message' => 'Llama a la entidad para activar tu tarjeta o usa otro medio de pago. El teléfono está al dorso de tu tarjeta.'],
            ['id' => '11', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_card_error', 'original_message' => 'No pudimos procesar tu pago.', 'user_message' => 'Lo sentimos, ocurrió un inconveniente. No pudimos procesar tu pago.'],
            ['id' => '12', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_duplicated_payment', 'original_message' => 'Ya hiciste un pago por ese valor. Si necesitas volver a pagar usa otra tarjeta u otro medio de pago.', 'user_message' => 'Ya hiciste un pago por ese valor. Si necesitas volver a pagar usa otra tarjeta u otro medio de pago.'],
            ['id' => '13', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_high_risk', 'original_message' => 'Tu pago fue rechazado. Elige otro de los medios de pago, te recomendamos con medios en efectivo.', 'user_message' => 'Tu pago fue rechazado. Elige otro de los medios de pago, te recomendamos con medios en efectivo.'],
            ['id' => '14', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_insufficient_amount', 'original_message' => 'Tu payment_method_id no tiene fondos suficientes.', 'user_message' => 'Lo sentimos, ocurrió un inconveniente. Tu medio de pago no tiene fondos suficientes.'],
            ['id' => '15', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_invalid_installments', 'original_message' => 'payment_method_id no procesa pagos en installments cuotas.', 'user_message' => 'Para el medio de pago ingresado, no se puede procesar los pagos en la cantidad de cuotas seleccionadas.'],
            ['id' => '16', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_max_attempts', 'original_message' => 'Llegaste al límite de intentos permitidos. Elige otra tarjeta u otro medio de pago.', 'user_message' => 'Llegaste al límite de intentos permitidos. Elige otra tarjeta u otro medio de pago.'],
            ['id' => '17', 'name' => 'Rechazado', 'success' => false, 'status' => 'rejected', 'status_detail' => 'cc_rejected_other_reason', 'original_message' => 'payment_method_id no procesó el pago.', 'user_message' => 'Lo sentimos, ocurrió un inconveniente. El pago no pudo ser procesado por el medio usado'],
        ]);

        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_states');
    }
}
