<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColaboratorToProductionAsText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('production', function (Blueprint $table) {
            //
            $table->text('production_collaborator')->nullable();
            $table->text('mix_collaborator')->nullable();
        });
        Schema::table('packaging', function (Blueprint $table) {
            //
            $table->text('packaging_collaborator')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('production', function (Blueprint $table) {
            //
            $table->dropColumn('production_collaborator');
            $table->dropColumn('mix_collaborator');
        });
        Schema::table('packaging', function (Blueprint $table) {
            //
            $table->dropColumn('packaging_collaborator');
        });
    }
}
