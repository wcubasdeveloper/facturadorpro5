<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantDownloadTrayTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('download_tray', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('module');
            $table->string('format');
            $table->string('path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('status')->default('IN_PROCESS');
            $table->datetime('date_init')->nullable();
            $table->datetime('date_end')->nullable();
            $table->text('payload_request')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('download_tray');
    }
}
