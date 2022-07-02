<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateCashDocumentsCredit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_document_credits', function (Blueprint $table) {
           
            $table->increments('id'); 
            $table->unsignedInteger('cash_id');
            $table->unsignedInteger('cash_id_processed')->nullable();
            $table->unsignedInteger('document_id')->nullable(); 
            $table->unsignedInteger('sale_note_id')->nullable();
            $table->string('status', 15)->default('PENDING');
            $table->timestamps();

            $table->foreign('document_id')->references('id')->on('documents'); 
            $table->foreign('sale_note_id')->references('id')->on('sale_notes');
            $table->foreign('cash_id')->references('id')->on('cash'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cash_document_credits');
    }
}
