<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

    /**
     * Class AddMethodTypeToDocumentsFee
     */
    class AddMethodTypeToDocumentsFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('document_fee', function (Blueprint $table) {
            //
            $table->char('payment_method_type_id',2)->nullable()->comment('Relacion con el metodo de pago, Nulo es pago a cuotas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_fee', function (Blueprint $table) {
            //
            $table->dropColumn('payment_method_type_id');

        });
    }
}
