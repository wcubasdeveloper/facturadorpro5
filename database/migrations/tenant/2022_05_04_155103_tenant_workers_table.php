<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantWorkersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workers', function (Blueprint $table) {

            $table->increments('id');
            $table->string('identity_document_type_id');
            $table->string('number')->index();
            $table->string('name')->index();
			$table->date('birth_date'); 
			$table->date('admission_date'); 
			$table->string('occupation');  
            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->timestamps();

            $table->foreign('identity_document_type_id')->references('id')->on('cat_identity_document_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('workers');
    }
}
