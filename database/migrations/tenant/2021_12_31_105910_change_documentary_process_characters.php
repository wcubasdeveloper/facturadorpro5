<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDocumentaryProcessCharacters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documentary_processes', function (Blueprint $table) {
            //
            $table->text('name')->change();
            $table->text('description')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documentary_processes', function (Blueprint $table) {
            //

            $table->string('name', 50)->change();
            $table->string('description', 250)->change();

        });
    }
}
