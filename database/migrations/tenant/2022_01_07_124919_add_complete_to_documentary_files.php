<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompleteToDocumentaryFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentary_files', function (Blueprint $table) {
            //
            $table->boolean('is_completed')->default(false);
            $table->unsignedInteger('establishment_id')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentary_files', function (Blueprint $table) {
            //
            $table->dropColumn('is_completed');
            $table->dropColumn('establishment_id');
        });
    }
}
