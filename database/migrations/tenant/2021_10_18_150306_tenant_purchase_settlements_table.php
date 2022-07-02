<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantPurchaseSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_settlements', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->uuid('external_id');
            $table->unsignedInteger('establishment_id');
            $table->json('establishment');
            $table->char('soap_type_id', 2);
            $table->char('state_type_id', 2);
            $table->string('ubl_version');
            $table->string('operation_type_id');
            $table->string('document_type_id');
            $table->char('series', 4)->index();
            $table->integer('number')->index();
            $table->date('date_of_issue')->index();
            $table->time('time_of_issue');
            $table->unsignedInteger('supplier_id');
            $table->json('supplier');
            $table->json('operation_data');
            $table->string('currency_type_id');
            $table->decimal('exchange_rate_sale', 12, 2);
            $table->decimal('total_prepayment', 12, 2)->default(0);
            $table->decimal('total_taxed', 12, 2)->default(0);
            $table->decimal('total_unaffected', 12, 2)->default(0);
            $table->decimal('total_exonerated', 12, 2)->default(0);
            $table->decimal('total_igv', 12, 2)->default(0);
            $table->decimal('total_taxes', 12, 2)->default(0);
            $table->decimal('total_value', 12, 2)->default(0);
            $table->decimal('total', 12, 2);

            $table->json('legends')->nullable();
            $table->json('prepayments')->nullable();
            $table->json('related')->nullable();
            $table->text('observations')->nullable();

            $table->string('filename')->nullable()->unique();
            $table->string('hash')->nullable();
            $table->boolean('has_xml')->default(false);
            $table->boolean('has_pdf')->default(false);
            $table->boolean('has_cdr')->default(false);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('establishment_id')->references('id')->on('establishments');
            $table->foreign('supplier_id')->references('id')->on('persons');
            $table->foreign('soap_type_id')->references('id')->on('soap_types');
            $table->foreign('state_type_id')->references('id')->on('state_types');
            $table->foreign('document_type_id')->references('id')->on('cat_document_types');
            $table->foreign('currency_type_id')->references('id')->on('cat_currency_types');
            $table->foreign('operation_type_id')->references('id')->on('cat_operation_types');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purchase_settlements');
    }

}
