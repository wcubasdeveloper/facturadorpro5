<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdditemDescriptionDefaultPdfNameConfiguration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('configurations', function (Blueprint $table) {
            $table->tinyInteger('item_name_pdf_description')->default(0)
                ->comment('Si esta activado, el nombre de pdf será por defecto la descripcion del item');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('configurations', function (Blueprint $table) {
            $table->dropColumn('item_name_pdf_description');
            //
        });
    }
}
