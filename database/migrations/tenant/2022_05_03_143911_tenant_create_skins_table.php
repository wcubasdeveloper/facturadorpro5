<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TenantCreateSkinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('skins', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('filename');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        DB::table('skins')->insert([
            ['name' => 'Default', 'filename' => 'default'],
            ['name' => 'Light', 'filename' => 'fastura.css'],
        ]);

        Schema::table('configurations', function (Blueprint $table) {
            $table->unsignedInteger('skin_id')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('skins');
        Schema::table('configurations', function (Blueprint $table) {
            $table->dropColumn('skin_id');
        });
    }
}
