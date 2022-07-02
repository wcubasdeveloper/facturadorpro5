<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_resources', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cpu_percent')->nullable();
            $table->string('memory_total')->nullable();
            $table->string('memory_free')->nullable();
            $table->string('memory_used')->nullable();
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
        Schema::dropIfExists('history_resources');
    }
}
