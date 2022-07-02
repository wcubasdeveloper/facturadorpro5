<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantAddOption13ToCatNoteCreditTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('cat_note_credit_types')->insert([
            ['id' => '13', 'active' => true, 'description' => 'Ajustes – montos y/o fechas de pago'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('cat_note_credit_types')->where('id', '13')->delete();
    }

}
