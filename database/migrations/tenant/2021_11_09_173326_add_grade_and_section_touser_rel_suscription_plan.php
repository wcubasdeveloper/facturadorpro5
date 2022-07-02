<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGradeAndSectionTouserRelSuscriptionPlan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_rel_suscription_plans', function (Blueprint $table) {
            //
             $table->text('grade')->nullable()->comment('Grado designado - utilizado en matricula');
             $table->text('section')->nullable()->comment('Seccion designado - utilizado en matricula');
        });
        Schema::table('sale_notes', function (Blueprint $table) {
            //
             $table->text('grade')->nullable()->comment('Grado designado - utilizado en matricula');
             $table->text('section')->nullable()->comment('Seccion designado - utilizado en matricula');
        });
        Schema::table('documents', function (Blueprint $table) {
            //
             $table->text('grade')->nullable()->comment('Grado designado - utilizado en matricula');
             $table->text('section')->nullable()->comment('Seccion designado - utilizado en matricula');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_rel_suscription_plans', function (Blueprint $table) {
            //
            $table->dropColumn('grade');
            $table->dropColumn('section');
        });
        Schema::table('sale_notes', function (Blueprint $table) {
            //
            $table->dropColumn('grade');
            $table->dropColumn('section');
        });
        Schema::table('documents', function (Blueprint $table) {
            //
            $table->dropColumn('grade');
            $table->dropColumn('section');
        });

    }
}
