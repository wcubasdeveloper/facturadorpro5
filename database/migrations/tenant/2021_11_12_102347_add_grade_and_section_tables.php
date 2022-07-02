<?php

    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGradeAndSectionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('suscription_grade', function($table)
        {
            $table->increments('id');
            $table->string('name', 100);

        });
        Schema::create('suscription_section', function($table)
        {
            $table->increments('id');
            $table->string('name', 100);

        });

        DB::table('suscription_grade')->insert([
            ['id' =>1, 'name' => '1er'],
            ['id' =>2, 'name' => '2do'],
            ['id' =>3, 'name' => '3ro'],
            ['id' =>4, 'name' => '4to'],
            ['id' =>5, 'name' => '5to'],
            ['id' =>6, 'name' => '6to'],
            ['id' =>7, 'name' => '7mo'],
            ['id' =>8, 'name' => '8vo'],
        ]);
        DB::table('suscription_section')->insert([
            ['id' =>1, 'name' => 'A'],
            ['id' =>2, 'name' => 'B'],
            ['id' =>3, 'name' => 'C'],
            ['id' =>4, 'name' => 'D'],
            ['id' =>5, 'name' => 'E'],
            ['id' =>6, 'name' => 'F'],
            ['id' =>7, 'name' => 'G'],
            ['id' =>8, 'name' => 'H'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('suscription_grade');
        Schema::dropIfExists('suscription_section');

    }
}
