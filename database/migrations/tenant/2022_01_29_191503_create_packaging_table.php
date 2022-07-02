<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePackagingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('packaging', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('item_id');
            $table->unsignedInteger('user_id')->nullable();
            $table->json('item_extra_data')->nullable();
            $table->unsignedInteger('establishment_id')->nullable();
            $table->decimal('quantity')->default(0)->nullable();
            $table->decimal('number_packages')->default(0)->nullable();
            $table->json('item')->nullable();
            $table->longText('observation')->nullable();
            $table->string('lot_code')->nullable();
            $table->string('name')->nullable();
            $table->date('date_start')->nullable();
            $table->time('time_start')->nullable();
            $table->date('date_end')->nullable();
            $table->time('time_end')->nullable();
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
        Schema::dropIfExists('packaging');
    }
}
